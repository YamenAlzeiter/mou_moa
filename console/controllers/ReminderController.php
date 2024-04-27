<?php

namespace console\controllers;

use Carbon\Carbon;
use common\models\Agreement;
use common\models\EmailTemplate;
use common\models\Reminder;
use Yii;
use yii\console\Controller;

class ReminderController extends Controller
{

    public function actionSendEmailReminders()
    {
        $remindEmailTemplate = EmailTemplate::findOne(10);
        $expiredEmailTemplate = EmailTemplate::findOne(12);

        if (!$remindEmailTemplate || !$expiredEmailTemplate) {
            echo "Email template not found.\n";
            return;
        }

        $reminders = Reminder::find()->all();
        $users = Agreement::find()->where(['not', ['end_date' => null]])->all();

        foreach ($users as $user) {
            $endDate = Carbon::createFromFormat('Y-m-d', $user->end_date);
            foreach ($reminders as $index => $reminder) {
                $remindDate = $reminder->type === 'MONTH' ? $endDate->copy()->subMonths($reminder->reminder_before)->startOfDay() : $endDate->copy()->subDays($reminder->reminder_before)->startOfDay();
                $currentDate = Carbon::now()->startOfDay();
                var_dump($currentDate->eq($remindDate) && $user->isReminded == $index);
                if ($currentDate->eq($remindDate) && $user->isReminded == $index && ($user->status == 91 || $user->status == 100)) {
                    // Send email reminder
                    $this->sendEmailReminder($user, $remindEmailTemplate);
                    $user->isReminded += 1;
                    $user->status = 110;
                    $user->save();
                }

                if ($currentDate > $user->end_date && ($user->status == 91 || $user->status == 110 || $user->status == 100)) {
                    $this->sendEmailReminder($user, $expiredEmailTemplate);
                    $user->status = 92;
                    $user->save();
                }
            }
        }

        echo "Reminders sent successfully.\n";
    }

    private function sendEmailReminder($user, $emailTemplate)
    {
        $body = str_replace('{recipientName}', $user->pi_name, $emailTemplate->body);
        Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], ['subject' => $emailTemplate->subject, 'body' => $body])->setFrom(["noreply@example.com" => "My Application"])->setTo($user->pi_email)->setSubject($emailTemplate->subject)->send();
    }


}

//
//
//use Carbon\Carbon;
//use common\models\Agreement;
//use common\models\EmailTemplate;
//use common\models\Reminder;
//use yii\console\Controller;
//use yii\queue\Queue;
//
//class ReminderController extends Controller
//{
//    public function actionSendEmailReminders()
//    {
//        $remindEmailTemplate = EmailTemplate::findOne(10);
//        $expiredEmailTemplate = EmailTemplate::findOne(12);
//
//        if (!$remindEmailTemplate || !$expiredEmailTemplate) {
//            echo "Email template not found.\n";
//            return;
//        }
//
//        $reminders = Reminder::find()->all();
//        $users = Agreement::find()->where(['not', ['end_date' => null]])->all();
//
//        /** @var Queue $queue */
//        $queue = Yii::$app->queue;
//
//        foreach ($users as $user) {
//            $endDate = Carbon::createFromFormat('Y-m-d', $user->end_date);
//            foreach ($reminders as $index => $reminder) {
//                $remindDate = $reminder->type === 'MONTH' ? $endDate->copy()->subMonths($reminder->reminder_before)->startOfDay() : $endDate->copy()->subDays($reminder->reminder_before)->startOfDay();
//                $currentDate = Carbon::now()->startOfDay();
//
//                if ($currentDate->eq($remindDate) && $user->isReminded == $index && ($user->status == 91 || $user->status == 100)) {
//                    // Queue the reminder job
//                    $queue->push(new SendEmailReminderJob([
//                        'userId' => $user->id,
//                        'templateId' => $remindEmailTemplate->id,
//                    ]));
//                    $user->isReminded += 1;
//                    $user->status = 110;
//                    $user->save();
//                }
//
//                if ($currentDate > $user->end_date && ($user->status == 91 || $user->status == 110 || $user->status == 100)) {
//                    // Queue the expired reminder job
//                    $queue->push(new SendEmailReminderJob([
//                        'userId' => $user->id,
//                        'templateId' => $expiredEmailTemplate->id,
//                    ]));
//                    $user->status = 92;
//                    $user->save();
//                }
//            }
//        }
//
//        echo "Reminders queued successfully.\n";
//    }
//}
//
//// Define a job class for sending email reminders
//class SendEmailReminderJob implements \yii\queue\JobInterface
//{
//    public $userId;
//    public $templateId;
//
//    public function execute($queue)
//    {
//        $user = Agreement::findOne($this->userId);
//        $emailTemplate = EmailTemplate::findOne($this->templateId);
//
//        if ($user && $emailTemplate) {
//            // Send email reminder
//            $this->sendEmailReminder($user, $emailTemplate);
//        } else {
//            // Log error or handle accordingly
//            Yii::error("Failed to send email reminder: User or template not found.");
//        }
//    }
//
//    private function sendEmailReminder($user, $emailTemplate)
//    {
//        $body = str_replace('{recipientName}', $user->pi_name, $emailTemplate->body);
//        Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], ['subject' => $emailTemplate->subject, 'body' => $body])->setFrom(["noreply@example.com" => "My Application"])->setTo($user->pi_email)->setSubject($emailTemplate->subject)->send();
//    }
//}
