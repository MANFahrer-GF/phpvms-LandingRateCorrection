<?php
return [
    // ═══════════════════════════════════════════════════════════
    // GENERAL UI
    // ═══════════════════════════════════════════════════════════
    'title'      => 'Landing Rate Corrections',
    'subtitle'   => 'Request a correction for an incorrectly recorded landing rate.',
    'tab_flights'=> 'My Flights',
    'tab_imp'    => 'Implausible',
    'tab_audit'  => 'My Requests',
    'tab_guide'  => 'Guide',
    'date'       => 'Date',
    'flight'     => 'Flight',
    'route'      => 'Route',
    'aircraft'   => 'Aircraft',
    'rate'       => 'Landing Rate',
    'status'     => 'Status',
    'details'    => 'Details',
    'action'     => 'Action',
    'original'   => 'Original',
    'requested'  => 'Requested',
    'decision'   => 'Admin Decision',
    'submitted'  => 'Submitted',
    'btn_fix'    => 'Request Fix',
    'btn_redo'   => 'Re-apply',
    'pending'    => 'Pending',
    'approved'   => 'Approved',
    'rejected'   => 'Rejected',
    'no_flights' => 'No completed flights found.',
    'no_imp'     => 'No implausible flights!',
    'no_req'     => 'No requests yet.',
    'imp_title'  => 'Implausible Landing Rates',
    'imp_desc'   => 'No value, positive, or shallower than -20 ft/min.',
    'your_reason'=> 'Your Reason',
    'admin_reply'=> 'Admin Response',
    'instead_of' => 'instead of',
    'awaiting'   => 'Awaiting review…',
    'no_val'     => 'no value',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - HERO & NAVIGATION
    // ═══════════════════════════════════════════════════════════
    'guide_title'        => 'Landing Rate Corrections – Guide',
    'guide_subtitle'     => 'Everything you need to know · Pilot section for all · Admin section for admins only',
    
    // Navigation Cards
    'guide_nav_what'           => 'What is this?',
    'guide_nav_what_sub'       => 'Overview',
    'guide_nav_rates'          => 'Landing Rates',
    'guide_nav_rates_sub'      => 'Reference table',
    'guide_nav_submit'         => 'Submit Request',
    'guide_nav_submit_sub'     => 'Step by step',
    'guide_nav_status'         => 'Request Status',
    'guide_nav_status_sub'     => 'What each means',
    'guide_nav_faq'            => 'FAQ',
    'guide_nav_faq_sub'        => 'Common questions',
    'guide_nav_admintabs'      => 'Admin Tabs',
    'guide_nav_admintabs_sub'  => 'Navigation',
    'guide_nav_review'         => 'Review',
    'guide_nav_review_sub'     => 'How to decide',
    'guide_nav_direct'         => 'Direct Fix',
    'guide_nav_direct_sub'     => 'Without request',
    'guide_nav_notify'         => 'Notifications',
    'guide_nav_notify_sub'     => 'Email setup',
    'guide_nav_navlinks'       => 'Nav Links',
    'guide_nav_navlinks_sub'   => 'Frontend',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: WHAT IS LRC
    // ═══════════════════════════════════════════════════════════
    'guide_what_title'   => 'What is a Landing Rate Correction?',
    'guide_what_p1'      => 'ACARS records your landing rate automatically when you touch down. Sometimes this fails – for example when your simulator crashes at the exact moment of touchdown, your internet connection drops, or ACARS has a software glitch. The result is a clearly wrong value like <strong>0 ft/min</strong>.',
    'guide_what_p2'      => 'This module lets you formally request a correction. You explain what happened, optionally attach a screenshot as proof, and an admin decides. If approved, your PIREP is updated automatically.',
    'guide_what_note'    => 'Only accepted PIREPs can be corrected. Draft or rejected PIREPs are not eligible.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: LANDING RATES
    // ═══════════════════════════════════════════════════════════
    'guide_rates_title'  => 'Landing Rate Reference',
    'guide_rates_intro'  => 'Use this when filling in your correction value:',
    'guide_rates_smooth' => 'Smooth',
    'guide_rates_smooth_range' => '-50 to -250 ft/min',
    'guide_rates_smooth_desc'  => 'Excellent landing',
    'guide_rates_normal' => 'Normal',
    'guide_rates_normal_range' => '-250 to -600 ft/min',
    'guide_rates_normal_desc'  => 'Acceptable',
    'guide_rates_hard'   => 'Hard',
    'guide_rates_hard_range'   => 'below -600 ft/min',
    'guide_rates_hard_desc'    => 'Hard landing',
    'guide_rates_implausible'  => 'Implausible',
    'guide_rates_implausible_range' => '0 or above -20 ft/min',
    'guide_rates_implausible_desc'  => 'Likely error',
    'guide_rates_warn'   => 'Only submit if you have actual proof. This is for correcting technical errors – not for improving statistics.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: SUBMIT REQUEST
    // ═══════════════════════════════════════════════════════════
    'guide_submit_title' => 'How to Submit a Request',
    'guide_submit_step1' => 'Find your flight in "My Flights" or "Implausible" tab',
    'guide_submit_step2' => 'Click "Request Fix" next to the flight',
    'guide_submit_step3' => 'Enter the correct landing rate you remember',
    'guide_submit_step4' => 'Explain why the recorded value is wrong',
    'guide_submit_step5' => 'Optionally attach a screenshot as proof',
    'guide_submit_step6' => 'Submit and wait for admin review',
    'guide_submit_tip'   => 'Screenshots from flight trackers or replay tools help admins review your request.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: REQUEST STATUS
    // ═══════════════════════════════════════════════════════════
    'guide_status_title'        => 'Request Status Explained',
    'guide_status_pending'      => 'Pending',
    'guide_status_pending_desc' => 'Waiting for admin review.',
    'guide_status_approved'     => 'Approved',
    'guide_status_approved_desc'=> 'Your PIREP has been updated with the corrected value.',
    'guide_status_rejected'     => 'Rejected',
    'guide_status_rejected_desc'=> 'Request denied. See reason in your request details.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: FAQ
    // ═══════════════════════════════════════════════════════════
    'guide_faq_title' => 'Frequently Asked Questions',
    'guide_faq_q1'    => 'Can I request a correction for any landing?',
    'guide_faq_a1'    => 'No. Only for obviously wrong values (0 ft/min, positive). Not for improving normal landings.',
    'guide_faq_q2'    => 'What proof do I need?',
    'guide_faq_a2'    => 'Ideally a screenshot from your flight tracker or replay tool. If unavailable, explain clearly.',
    'guide_faq_q3'    => 'How long does review take?',
    'guide_faq_a3'    => 'Usually a few days. You\'ll be notified by email if enabled.',
    'guide_faq_q4'    => 'Can I resubmit after rejection?',
    'guide_faq_a4'    => 'Yes, with new evidence. Use the "Re-apply" button.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - ADMIN SECTIONS
    // ═══════════════════════════════════════════════════════════
    'guide_admin_only' => 'Admin Section',
    
    // Admin Tabs
    'guide_admintabs_title' => 'Admin Panel Navigation',
    'guide_admintabs_intro' => 'The admin area has these tabs:',
    'guide_admintabs_requests'      => 'Pending Requests',
    'guide_admintabs_requests_desc' => 'Open pilot requests for review',
    'guide_admintabs_history'       => 'History',
    'guide_admintabs_history_desc'  => 'Processed requests archive',
    'guide_admintabs_implausible'   => 'Implausible PIREPs',
    'guide_admintabs_implausible_desc' => 'PIREPs with suspicious values',
    'guide_admintabs_notifications' => 'Notifications',
    'guide_admintabs_notifications_desc' => 'Email templates & recipients',
    'guide_admintabs_appearance'    => 'Appearance',
    'guide_admintabs_appearance_desc' => 'Glass/Solid & colors',

    // Review Process
    'guide_review_title'  => 'Reviewing Requests',
    'guide_review_intro'  => 'When reviewing, consider:',
    'guide_review_check1' => 'Is the original value clearly wrong?',
    'guide_review_check2' => 'Does the explanation make sense?',
    'guide_review_check3' => 'Does evidence support the claim?',
    'guide_review_check4' => 'Is the requested value realistic?',
    'guide_review_tip'    => 'When in doubt, ask for more info before rejecting.',

    // Direct Fix
    'guide_direct_title'  => 'Direct Correction',
    'guide_direct_intro'  => 'Fix a PIREP without pilot request – useful for obvious errors.',
    'guide_direct_step1'  => 'Go to "Implausible PIREPs"',
    'guide_direct_step2'  => 'Find the affected flight',
    'guide_direct_step3'  => 'Click "Fix" and enter correct value',
    'guide_direct_step4'  => 'PIREP is updated immediately',
    'guide_direct_note'   => 'Direct fixes are logged. Pilot sees corrected value.',

    // Notifications
    'guide_notify_title'  => 'Email Notifications',
    'guide_notify_intro'  => 'Notifications can be sent for:',
    'guide_notify_event1' => 'New requests (to admins)',
    'guide_notify_event2' => 'Approved (to pilot)',
    'guide_notify_event3' => 'Rejected (to pilot)',
    'guide_notify_setup'  => 'Configure in "Notifications" tab.',

    // Nav Links
    'guide_navlinks_title'     => 'Frontend Navigation',
    'guide_navlinks_intro'     => 'Add to your theme navigation:',
    'guide_navlinks_tbl_who'   => 'Who',
    'guide_navlinks_tbl_url'   => 'URL',
    'guide_navlinks_tbl_desc'  => 'Description',
    'guide_navlinks_pilot'     => 'Pilot',
    'guide_navlinks_pilot_desc'=> 'Pilot Dashboard',
    'guide_navlinks_admin'     => 'Admin',
    'guide_navlinks_admin_desc'=> 'Admin Panel',
    'guide_navlinks_admin_imp' => 'Implausible + Direct Fix',
];
