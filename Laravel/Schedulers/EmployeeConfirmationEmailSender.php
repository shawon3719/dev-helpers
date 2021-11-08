<?php
namespace App\Schedulers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmployeeConfirmationEmailSender
{

    public function process()
    {
        if (env('EMAIL_NOTIFICATION', false) == false) {
            return false;
        }

        $employee_confirmation_send_before = getOption('employee_confirmation_send_before', 1);
        
        $notifyDate = Carbon::now()->addDays($employee_confirmation_send_before)->format('Y-m-d');

        $employees = Employee::join('users as u', 'employees.reporting_boss', '=', 'u.id')
        ->select([
            'u.email',
            'employees.employee_code as code',
            'employees.employee_name as name',
            'employees.confirmed_on'
        ])
        ->where('employees.confirmed_on', '=', $notifyDate)
        ->where('employees.employee_status', 'active')
        ->get();

        if($employees->count() > 0){
            $notifyTo = getOption('employee_confirmation_email', "");

            $notifyToArray = [];
            if(!empty($notifyTo)){
                $notifyToArray = explode(',', $notifyTo);
            }

            foreach($employees as $employee){

                if(empty($employee->email)){
                    continue;
                }

                $data = [
                    'employeeName' => $employee->name,
                    'employeeCode' => $employee->code,
                    'confirmedOn' => $employee->confirmed_on,
                ];

                Mail::send('emails.employee_confirmation', $data, function ($message) use ($employee, $notifyToArray) {
                    $message->to($employee->email);
                    $message->subject('Employee Confirmation Notification');
                    if(count($notifyToArray) > 0){
                        $message->cc($notifyToArray);
                    }
                });
            }
        }
        return true;
    }
}