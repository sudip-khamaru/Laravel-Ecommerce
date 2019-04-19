<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\ValidateOrder;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Stripe\Charge;
use Stripe\Stripe;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // dd( Auth::user() );
        if( !Session::has( 'cart' ) || empty( Session::get( 'cart' )->getItems() ) ) {

            return redirect( 'products' )->with( 'message', "Cart is empty!" );

        }

        $cart = Session::get( 'cart' );

        return view( 'checkout.index', compact( 'cart' ) );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateOrder $request)
    {
        
        $error = '';
        $success = '';
        $cart = array();
        $order = '';
        $customer_store = '';
              
        Stripe::setApiKey("sk_test_GupfYlFbKh39tG9CqAX2lLbs00NjFnj802");
        
        if( Session::has( 'cart' ) ) {

            $cart = Session::get( 'cart' );

            $charge = Charge::create( [ // Stripe own table
            
                'amount'        =>  $cart->getTotalPrice() * 100,
                'currency'      =>  'usd',
                'source'        =>  $request->stripeToken,
                'receipt_email' =>  $request->billing_email,
            
            ] );

        }

        // return $charge;
        
        if( isset( $charge ) ) {

            if( $request->same_address ) {

                $customer = [

                    'billing_first_name'    =>  $request->billing_first_name,
                    'billing_last_name'     =>  $request->billing_last_name,
                    'billing_username'      =>  $request->billing_username,
                    'billing_email'         =>  $request->billing_email,
                    'billing_address1'      =>  $request->billing_address1,
                    'billing_address2'      =>  $request->billing_address2,
                    'billing_country'       =>  $request->billing_country,
                    'billing_state'         =>  $request->billing_state,
                    'billing_zip'           =>  $request->billing_zip,

                    'shipping_first_name'   =>  $request->shipping_first_name,
                    'shipping_last_name'    =>  $request->shipping_last_name,
                    'shipping_address1'     =>  $request->shipping_address1,
                    'shipping_address2'     =>  $request->shipping_address2,
                    'shipping_country'      =>  $request->shipping_country,
                    'shipping_state'        =>  $request->shipping_state,
                    'shipping_zip'          =>  $request->shipping_zip,
                
                ];
            
            } else {

                $customer = [
                
                    'billing_first_name'    => $request->billing_first_name,
                    'billing_last_name'     => $request->billing_last_name,
                    'billing_username'      => $request->billing_username,
                    'billing_email'         => $request->billing_email,
                    'billing_address1'      => $request->billing_address1,
                    'billing_address2'      => $request->billing_address2,
                    'billing_country'       => $request->billing_country,
                    'billing_state'         => $request->billing_state,
                    'billing_zip'           => $request->billing_zip,
                
                ];

            }

        }

        DB::beginTransaction();

        $customer_store = Customer::create( $customer );
        
        foreach( $cart->getItems() as $slug => $product ) {

            $products = [

                'user_id'       =>  $customer_store->id,
                'product_id'    =>  $product[ 'product' ]->id,
                'qty'           =>  $product[ 'qty' ],
                'status'        =>  'Pending',
                'price'         =>  $product[ 'price' ],
                'payment_id'    =>  ( isset( $charge ) ) ? $charge->id : 0,
            
            ];
            
            $order = Order::create( $products );
        
        }

        if( $customer_store && $order ) {

            DB::commit();

            $request->session()->forget( 'cart' );

            return redirect( 'products' )->with( 'message', "Your order successfully processed!" );
            
        } else {

            DB::rollback();

            return redirect( 'checkout' )->with( 'message', 'Payment not done or invalid activity!' );
            
        }

        return response()->json( $order );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function paypalPaymentGateway( ValidateOrder $request )
    {

        if( Session::has( 'cart' ) ) {
        
            $cart = Session::get( 'cart' );

            $apiContext = new ApiContext(

                new OAuthTokenCredential(

                    env( 'PAYPAL_APP_CLIENT_ID' ),
                    env( 'PAYPAL_APP_SECRET' )

                )

            );

            // Create new payer and method
            $payer = new Payer();
            $payer->setPaymentMethod( "paypal" );

            // Set redirect URLs
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl( route( 'payment.processPaypalPayment' ) )
                         ->setCancelUrl( route( 'payment.cancelPaypalPayment' ) );

            // Set payment amount
            $amount = new Amount();
            $amount->setCurrency( "USD" )
                   ->setTotal( $cart->getTotalPrice() );

            // Set transaction object
            $transaction = new Transaction();
            $transaction->setAmount( $amount )
                        ->setDescription( "Payment description" );

            // Create the full payment object
            $payment = new Payment();
            $payment->setIntent( 'sale' )
                    ->setPayer( $payer )
                    ->setRedirectUrls( $redirectUrls )
                    ->setTransactions( array( $transaction ) );

            // Create payment with valid API context
            try {

                $payment->create( $apiContext );

                // Get PayPal redirect URL and redirect the customer
                $approvalUrl = $payment->getApprovalLink();

                if( $request->same_address ) {

                    $customer = [

                        'billing_first_name'    =>  $request->billing_first_name,
                        'billing_last_name'     =>  $request->billing_last_name,
                        'billing_username'      =>  $request->billing_username,
                        'billing_email'         =>  $request->billing_email,
                        'billing_address1'      =>  $request->billing_address1,
                        'billing_address2'      =>  $request->billing_address2,
                        'billing_country'       =>  $request->billing_country,
                        'billing_state'         =>  $request->billing_state,
                        'billing_zip'           =>  $request->billing_zip,

                        'shipping_first_name'   =>  $request->shipping_first_name,
                        'shipping_last_name'    =>  $request->shipping_last_name,
                        'shipping_address1'     =>  $request->shipping_address1,
                        'shipping_address2'     =>  $request->shipping_address2,
                        'shipping_country'      =>  $request->shipping_country,
                        'shipping_state'        =>  $request->shipping_state,
                        'shipping_zip'          =>  $request->shipping_zip,

                        'same_address'             =>  $request->same_address,
                    
                    ];
                
                } else {

                    $customer = [
                    
                        'billing_first_name'    => $request->billing_first_name,
                        'billing_last_name'     => $request->billing_last_name,
                        'billing_username'      => $request->billing_username,
                        'billing_email'         => $request->billing_email,
                        'billing_address1'      => $request->billing_address1,
                        'billing_address2'      => $request->billing_address2,
                        'billing_country'       => $request->billing_country,
                        'billing_state'         => $request->billing_state,
                        'billing_zip'           => $request->billing_zip,
                    
                    ];

                }

                // Store it into session
                Session::put( 'customer', json_encode( $customer ) );

                // Redirect the customer to $approvalUrl
                return redirect( $approvalUrl );

            } catch( PayPalConnectionException $ex ) {

                echo $ex->getCode();
                echo $ex->getData();
                
                die( $ex );
            
            } catch( Exception $ex ) {

                die( $ex );
            
            }

        } else {

            return redirect( 'checkout' )->with( 'message', 'Payment not done or invalid activity!' );

        }

    }

    public function processPaypalPayment( Request $request )
    {

        $cart = Session::get( 'cart' );

        $apiContext = new ApiContext(

            new OAuthTokenCredential(

                env( 'PAYPAL_APP_CLIENT_ID' ),
                env( 'PAYPAL_APP_SECRET' )

            )

        );

        // Get payment object by passing paymentId
        $paymentId = $request->paymentId;
        $payment = Payment::get( $paymentId, $apiContext );
        $payerId = $request->PayerID;

        // Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId( $payerId );

        try {
            
            // Execute payment
            $result = $payment->execute( $execution, $apiContext );
            
            // dd( $result );

            if( isset( $result ) && strtolower( $result->state ) === "approved" ) {

                $customer_session = json_decode( Session::get( 'customer' ) );

                if( $customer_session->same_address ) {

                    $customer = [

                        'billing_first_name'    =>  $customer_session->billing_first_name,
                        'billing_last_name'     =>  $customer_session->billing_last_name,
                        'billing_username'      =>  $customer_session->billing_username,
                        'billing_email'         =>  $customer_session->billing_email,
                        'billing_address1'      =>  $customer_session->billing_address1,
                        'billing_address2'      =>  $customer_session->billing_address2,
                        'billing_country'       =>  $customer_session->billing_country,
                        'billing_state'         =>  $customer_session->billing_state,
                        'billing_zip'           =>  $customer_session->billing_zip,

                        'shipping_first_name'   =>  $customer_session->shipping_first_name,
                        'shipping_last_name'    =>  $customer_session->shipping_last_name,
                        'shipping_address1'     =>  $customer_session->shipping_address1,
                        'shipping_address2'     =>  $customer_session->shipping_address2,
                        'shipping_country'      =>  $customer_session->shipping_country,
                        'shipping_state'        =>  $customer_session->shipping_state,
                        'shipping_zip'          =>  $customer_session->shipping_zip,

                    ];
                
                } else {

                    $customer = [
                    
                        'billing_first_name'    => $customer_session->billing_first_name,
                        'billing_last_name'     => $customer_session->billing_last_name,
                        'billing_username'      => $customer_session->billing_username,
                        'billing_email'         => $customer_session->billing_email,
                        'billing_address1'      => $customer_session->billing_address1,
                        'billing_address2'      => $customer_session->billing_address2,
                        'billing_country'       => $customer_session->billing_country,
                        'billing_state'         => $customer_session->billing_state,
                        'billing_zip'           => $customer_session->billing_zip,
                    
                    ];

                }

                DB::beginTransaction();

                $customer_store = Customer::create( $customer );
                
                foreach( $cart->getItems() as $slug => $product ) {

                    $products = [

                        'user_id'       =>  $customer_store->id,
                        'product_id'    =>  $product[ 'product' ]->id,
                        'qty'           =>  $product[ 'qty' ],
                        'status'        =>  'Pending',
                        'price'         =>  $product[ 'price' ],
                        'payment_id'    =>  ( isset( $result ) ) ? $result->id : 0,
                    
                    ];
                    
                    $order = Order::create( $products );
                
                }

                if( $customer_store && $order ) {

                    DB::commit();

                    $request->session()->forget( 'cart' );

                    return redirect( 'products' )->with( 'message', "Your order successfully processed!" );
                    
                } else {

                    DB::rollback();

                    return redirect( 'checkout' )->with( 'message', 'Payment not done or invalid activity!' );
                    
                }
            
            } else {

                return redirect( 'checkout' )->with( 'message', 'Payment not done or invalid activity!' );

            }
        
        } catch( PayPalConnectionException $ex ) {

            echo $ex->getCode();
            echo $ex->getData();
            
            die( $ex );

        } catch( Exception $ex ) {

            die( $ex );

        }

    }

    public function cancelPaypalPayment()
    {


        
    }
}
