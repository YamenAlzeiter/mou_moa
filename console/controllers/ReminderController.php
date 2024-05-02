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

                if ($currentDate->eq($remindDate) && ($user->status == 91 || $user->status == 100)) {
                    // Send email reminder
                    $this->sendEmailReminder($user, $remindEmailTemplate);
                    $user->isReminded += 1;
                    $user->status = 110;
                    $user->save();
                } elseif ($currentDate->greaterThan($remindDate) && ($user->status == 91 || $user->status == 110) && !($currentDate > $user->end_date)) {
                    if ($user->isReminded == $index) {
                        $this->sendEmailReminder($user, $remindEmailTemplate);
                        $user->isReminded += 1;
                        $user->status = 110;
                        $user->save();
                    }
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
        if ($user->pi_email != null) {
            $body = str_replace('{recipientName}', $user->pi_name, $emailTemplate->body);
            Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], ['subject' => $emailTemplate->subject, 'body' => $body])->setFrom(["noreply@example.com" => "My Application"])->setTo($user->pi_email)->setSubject($emailTemplate->subject)->send();
        }
    }

    public function actionSendActivityReminders()
    {
        $activityReminderEmailTemplate = EmailTemplate::findOne(10);

        if (!$activityReminderEmailTemplate) {
            echo "Email template not found.\n";
            return;
        }


        $users = Agreement::find()->andWhere(['not', ['sign_date' => null]])->andWhere(['status' => [100, 91]])->all();


        foreach ($users as $user) {
            if ($user->last_reminder == null) {
                $user->last_reminder = Carbon::now()->copy()->addDays(7)->startOfDay();
                $user->save();
            } else {
                $currentDate = Carbon::now()->startOfDay();
                if ($currentDate->eq($user->last_reminder)) {
                    $this->sendEmailReminder($user, $activityReminderEmailTemplate);
                    $user->last_reminder = Carbon::createFromFormat('Y-m-d', $user->last_reminder)->copy()->addMonths(3)->toDateString();
                    $user->save();
                }

            }

        }

    }

}

