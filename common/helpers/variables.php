<?php
namespace common\helpers;
class Variables
{
    //status
    const agreement_init = 10;
    const agreement_resubmitted = 15;
    const agreement_MCOM_date_set = 21;
    const agreement_approved_osc = 1;
    const agreement_not_complete_osc = 2;
    const agreement_approved_ola = 11;
    const agreement_not_complete_ola = 12;
    const agreement_approved_circulation = 13;

    const agreement_approved_via_power = 14;

    const agreement_MCOM_approved = 31;
    const agreement_MCOM_reject = 32;
    const agreement_MCOM_KIV = 33;
    const agreement_UMC_approve = 41;
    const agreement_UMC_reject = 42;
    const agreement_UMC_KIV = 43;
    const agreement_conditional_upload = 46;
    const agreement_conditional_upload_not_complete = 47;
    const agreement_draft_uploaded_ola = 51;
    const agreement_draft_upload_applicant = 61;
    const agreement_draft_approved_ola = 71;
    const agreement_draft_rejected_ola = 72;
    const agreement_draft_approve_final_draft = 81;
    const agreement_rejected = 82;
    const agreement_executed = 91;
    const agreement_expired = 92;
    const imported_agreement_executed = 100;
    const imported_agreement_expired = 102;
    const agreement_reminder_sent = 110;
    const agreement_extended = 111;
    const agreement_MCOM_date_changed = 121;



    //email templates
    const email_init = 1;
    const email_agr_complete_osc =23;
    const email_agr_not_complete = 2;
    const email_agr_reject = 3;
    const email_agr_review_complete_ola = 4;
    const email_agr_review_not_complete_ola = 5;
    const email_agr_pick_mcom_date = 6;
    const email_agr_mcom_date_change = 7;
    const email_agr_mcom_approve = 8;
    const email_agr_mcom_kiv = 9;
    const email_agr_mcom_reject = 10;
    const email_agr_mcom_resubmitted = 11;
    const email_umc_approve = 12;
    const email_umc_kiv = 13;
    const email_umc_reject = 14;
    const email_draft_upload_ola = 15;
    const email_draft_upload_applicant = 16;
    const email_draft_approve = 17;
    const email_draft_not_complete = 18;
    const email_agr_executed = 19;
    const email_progress_reminder = 20;
    const email_expiry_date_reminder = 21;
    const email_agr_expired = 22;

//Email Template

}