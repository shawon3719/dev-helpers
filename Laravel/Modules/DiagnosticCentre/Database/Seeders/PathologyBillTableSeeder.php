<?php

namespace Modules\DiagnosticCentre\Database\Seeders;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Modules\DiagnosticCentre\Entities\bill;
use Illuminate\Support\Str;
use Modules\DiagnosticCentre\Entities\Item;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyBillingDetails;
use Modules\DiagnosticCentre\Entities\Payment;
use Modules\DiagnosticCentre\Entities\PaymentDetails;
use Modules\DiagnosticCentre\Services\PathologyBillsService;

class PathologyBillTableSeeder extends Seeder
{
    protected $pathologyBillsService;

    public function __construct()
    {
        $this->pathologyBillsService = new PathologyBillsService(); 
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker  =   Factory::create();

        for($i=1; $i<=1000; $i++){

            $date   =   $faker->dateTimeBetween('-2 years', 'now', 'UTC')->format('Y-m-d');
            $delivery_date = $faker->dateTimeBetween($date, '+2 days')->format('Y-m-d');
            $delivery_time = Carbon::parse(now())->format('H:i:s');

            $created_by = User::inRandomOrder()->first()->id;

            $items = Item::inRandomOrder()->limit($faker->numberBetween(1,7))->get();

            $sub_total =  0;

            $tax = $faker->numberBetween(100,200);
            $discount = $faker->numberBetween(100,200);

            $item_discount_percentage = array();
            $item_discount_amount = array();
            $item_actual_price = array();


            foreach($items as $item){
                $this_item_price = $item->offer_price != null ? $item->offer_price : $item->price;

                $item_actual_price[$item->id] = $this_item_price;
                $item_discount_percentage[$item->id] = $faker->numberBetween(0,5);
                $item_discount_amount[$item->id] = ( $item_discount_percentage[$item->id] *  $item_actual_price[$item->id])/100;

                $sub_total += $this_item_price - $item_discount_amount[$item->id];
            }

            $will_be_less = $tax + $discount;
            $total = $sub_total - $will_be_less;

            $received_amount = $faker->numberBetween(100,$total+2000);

            $due_amount = $received_amount >= $total ? 0 : $total - $received_amount;


            $code = $this->pathologyBillsService->generatePathologyBillCode();
            
            $pathology_bill = PathologyBilling::create(
                [
                    'code' => $code,
                    'patient_id'        => Patient::inRandomOrder()->first()->id,
                    'referrer_id'       => Doctor::inRandomOrder()->first()->id,
                    'bill_date'         => $date,
                    'delivery_date'     => $delivery_date,
                    'delivery_time'     => '06:30:00', //$delivery_time,                
                    'remarks'           => $faker->word,                
                    'sub_total'         => $sub_total,                
                    'tax'               => $tax,                
                    'discount'          => $discount,                
                    'total'             => $total,                
                    'created_by'        => $created_by,
                    'created_at'        => $date, 
                ]);

                foreach($items as $item){
                    PathologyBillingDetails::create([
                        'pathology_billing_id'  => $pathology_bill->id,
                        'category_id'           => $item->category->id,
                        'item_id'               => $item->id,
                        'price'                 => $item_actual_price[$item->id],
                        'quantity'              => 1,
                        'discount_percentage'   => $item_discount_percentage[$item->id],
                        'discount_amount'       => $item_discount_amount[$item->id],
                        'total'                 => $item_actual_price[$item->id] - $item_discount_amount[$item->id],
                        'created_by'            => $created_by,
                        'created_at'            => $date, 
                    ]);
                }

                $payment = Payment::create([
                    'payment_reference'     => class_basename($pathology_bill),
                    'payment_reference_id'  => $pathology_bill->id,
                    'net_payable'           => $total,
                    'received_amount'       => $received_amount >= $total ? $total : $received_amount,
                    'due_amount'            => $due_amount,
                    'created_by'            => $created_by,
                    'created_at'            => $date, 
                ]);

                PaymentDetails::create([
                    'payment_id'    => $payment->id,
                    'pay_via'       => array_rand(Config::get('diagnosticcentre.payment_type'), 1),
                    'amount'        => $received_amount >= $total ? $total : $received_amount,
                    'created_by'    => $created_by,
                    'created_at'    => $date, 
                ]);



        }
    }
}
