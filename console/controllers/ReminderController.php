<?php

namespace console\controllers;

use Carbon\Carbon;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\EmailTemplate;
use common\models\Reminder;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

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
        $models = Agreement::find()->where(['not', ['end_date' => null]])->all();

        foreach ($models as $model) {
            $endDate = Carbon::createFromFormat('Y-m-d', $model->end_date);
            foreach ($reminders as $index => $reminder) {
                if ($reminder->type === 'MONTH') {
                    $remindDate = $endDate->copy()->subMonths($reminder->reminder_before)->startOfDay();
                } else {
                    $remindDate = $endDate->copy()->subDays($reminder->reminder_before)->startOfDay();
                }

                $currentDate = Carbon::now()->startOfDay();

//                var_dump('record is : ' . $model->id . " " . $model->end_date . " " . $remindDate);

                if ($currentDate->eq($remindDate) && ($model->status == 91 || $model->status == 100)) {
                    $users = AgreementPoc::find()->where(['agreement_id' => $model->id])->all();
                    // Send email reminder
                    $this->sendEmailReminder($users, $remindEmailTemplate);
                    $model->isReminded += 1;
                    $model->status = 110;
                    $model->save();
                } elseif ($currentDate->greaterThan($remindDate) && ($model->status == 91 || $model->status == 100) && !($currentDate > $model->end_date)) {

                    if ($model->isReminded == $index) {
                        $users = AgreementPoc::find()->where(['agreement_id' => $model->id])->all();
                        $this->sendEmailReminder($users, $remindEmailTemplate);
                        $model->isReminded += 1;
                        $model->status = 110;
                        $model->save();
                    }
                }

                if ($currentDate > $model->end_date && ($model->status == 91 || $model->status == 110 || $model->status == 100)) {
                    $users = AgreementPoc::find()->where(['agreement_id' => $model->id])->all();
                    $this->sendEmailReminder($users, $remindEmailTemplate);
                    $model->status = 92;
                    $model->save();
                }
            }
        }

        echo "Reminders sent successfully.\n";
    }

    private function sendEmailReminder($users, $emailTemplate)
    {
        $primaryUser = null;
        $ccEmails = [];

        foreach ($users as $user) {
            if ($user->pi_is_primary) {
                $primaryUser = $user;
            } else {
                if ($user->pi_email != null) {
                    $ccEmails[] = $user->pi_email;
                }
            }
        }

        if ($primaryUser !== null) {
            $body = str_replace('{user}', $primaryUser->pi_name, $emailTemplate->body);
            $body = str_replace('{id}', $primaryUser->agreement_id, $body);

            $mailer = Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], [
                'subject' => $emailTemplate->subject,
                'body' => $body,
            ])
                ->setFrom(["noreply@example.com" => "My Application"])
                ->setTo($primaryUser->pi_email)
                ->setSubject($emailTemplate->subject);

            if (!empty($ccEmails)) {
                $mailer->setCc($ccEmails);
            }

            $mailer->send();
        }

    }

    /**
     * @throws Exception
     */
    public function actionSendActivityReminders()
    {
        $activityReminderEmailTemplate = EmailTemplate::findOne(10);

        if (!$activityReminderEmailTemplate) {
            echo "Email template not found.\n";
            return;
        }


        $users = Agreement::find()->andWhere(['not', ['sign_date' => null]])->andWhere(['status' => [100, 91]])->all();


        foreach ($users as $user) {
            var_dump($user->id);
            if ($user->last_reminder == null) {
                $user->last_reminder = Carbon::now()->copy()->addDays(7)->startOfDay()->toDateString();


            } else {
                $currentDate = Carbon::now()->startOfDay();
                if ($currentDate->eq($user->last_reminder)) {
                    $pocs = AgreementPoc::find()->where(['agreement_id' => $user->id])->all();

                    $this->sendEmailReminder($pocs, $activityReminderEmailTemplate);
                    $user->last_reminder = Carbon::createFromFormat('Y-m-d', $user->last_reminder)->copy()->addMonths(3)->toDateString();

                }

            }
            if (!$user->save()) {
                echo "Failed to save user with ID {$user->id}.\n";
                print_r($user->errors);
            } else {
                echo "Successfully saved user with ID {$user->id}.\n";
            }

        }

    }

}

