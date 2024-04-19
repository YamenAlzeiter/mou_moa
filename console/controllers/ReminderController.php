<?php

namespace console\controllers;

use Carbon\Carbon;
use common\models\admin;
use common\models\Agreement;
use common\models\EmailTemplate;
use Yii;
use yii\console\Controller;

class ReminderController extends Controller
{

    public function actionSendEmailReminders()
    {
        $mailer = Yii::$app->mailer;
        $emailTemplate = EmailTemplate::findOne(10);

        // Fetch records where the end_date is within the specified intervals
        $users = Agreement::find()->all();
        foreach ($users as $user) {
            // Check if end_date is null
            if ($user->end_date !== null) {
                $endDate = Carbon::createFromFormat('Y-m-d', $user->end_date);

                // Calculate reminder dates
                $threeMonthsBefore = $endDate->copy()->subMonths(3)->startOfDay();
                $twoMonthsBefore = $endDate->copy()->subMonths(2)->startOfDay();
                $oneMonthBefore = $endDate->copy()->subMonths(1)->startOfDay();
                $tenDaysBefore = $endDate->copy()->subDays(10)->startOfDay();

                // Check if the current date matches any of the reminder dates
                $currentDate = Carbon::now()->startOfDay();
                if ($currentDate->eq($threeMonthsBefore) ||
                    $currentDate->eq($twoMonthsBefore) ||
                    $currentDate->eq($oneMonthBefore) ||
                    $currentDate->eq($tenDaysBefore)) {

                    $recipientEmail = $user->pi_email;
                    $osc = Admin::findOne(['type' => $user->transfer_to]);

                    if ($user->status == 100 || $user->status == 91) {
                        $body = $emailTemplate->body;

                        $body = str_replace('{recipientName}', $user->pi_name, $body);

                        Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], [
                            'subject' => $emailTemplate->subject, 'body' => $body
                        ])->setFrom(["noreply@example.com" => "My Application"])->setTo($recipientEmail)->setSubject($emailTemplate->subject)->send();

                        $user->status = 110;
                        $user->save();
                    }
                }
            }
        }

        echo "Reminders sent successfully.\n";
    }


}
