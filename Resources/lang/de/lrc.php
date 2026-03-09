<?php
return [
    // ═══════════════════════════════════════════════════════════
    // GENERAL UI
    // ═══════════════════════════════════════════════════════════
    'title'      => 'Landeratenkorrekturen',
    'subtitle'   => 'Beantrage die Korrektur einer falsch aufgezeichneten Landerate.',
    'tab_flights'=> 'Meine Flüge',
    'tab_imp'    => 'Unplausibel',
    'tab_audit'  => 'Meine Anträge',
    'tab_guide'  => 'Handbuch',
    'date'       => 'Datum',
    'flight'     => 'Flug',
    'route'      => 'Route',
    'aircraft'   => 'Flugzeug',
    'rate'       => 'Landerate',
    'status'     => 'Status',
    'details'    => 'Details',
    'action'     => 'Aktion',
    'original'   => 'Original',
    'requested'  => 'Beantragt',
    'decision'   => 'Admin-Entscheidung',
    'submitted'  => 'Eingereicht',
    'btn_fix'    => 'Korrektur beantragen',
    'btn_redo'   => 'Erneut beantragen',
    'pending'    => 'Ausstehend',
    'approved'   => 'Genehmigt',
    'rejected'   => 'Abgelehnt',
    'no_flights' => 'Keine abgeschlossenen Flüge gefunden.',
    'no_imp'     => 'Keine unplausiblen Flüge!',
    'no_req'     => 'Noch keine Anträge.',
    'imp_title'  => 'Unplausible Landeraten',
    'imp_desc'   => 'Kein Wert, positiv, oder flacher als -20 ft/min.',
    'your_reason'=> 'Deine Begründung',
    'admin_reply'=> 'Admin-Antwort',
    'instead_of' => 'statt',
    'awaiting'   => 'Wartet auf Prüfung…',
    'no_val'     => 'kein Wert',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - HERO & NAVIGATION
    // ═══════════════════════════════════════════════════════════
    'guide_title'        => 'Landeratenkorrekturen – Handbuch',
    'guide_subtitle'     => 'Alles was du wissen musst · Pilotenbereich für alle · Admin-Bereich nur für Admins',
    
    // Navigation Cards
    'guide_nav_what'           => 'Was ist das?',
    'guide_nav_what_sub'       => 'Überblick',
    'guide_nav_rates'          => 'Landeraten',
    'guide_nav_rates_sub'      => 'Referenztabelle',
    'guide_nav_submit'         => 'Antrag stellen',
    'guide_nav_submit_sub'     => 'Schritt für Schritt',
    'guide_nav_status'         => 'Antragsstatus',
    'guide_nav_status_sub'     => 'Was jeder bedeutet',
    'guide_nav_faq'            => 'FAQ',
    'guide_nav_faq_sub'        => 'Häufige Fragen',
    'guide_nav_admintabs'      => 'Admin-Tabs',
    'guide_nav_admintabs_sub'  => 'Navigation',
    'guide_nav_review'         => 'Prüfen',
    'guide_nav_review_sub'     => 'Wie entscheiden',
    'guide_nav_direct'         => 'Direktkorrektur',
    'guide_nav_direct_sub'     => 'Ohne Antrag',
    'guide_nav_notify'         => 'Benachrichtigungen',
    'guide_nav_notify_sub'     => 'E-Mail-Setup',
    'guide_nav_navlinks'       => 'Nav-Links',
    'guide_nav_navlinks_sub'   => 'Frontend',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: WHAT IS LRC
    // ═══════════════════════════════════════════════════════════
    'guide_what_title'   => 'Was ist eine Landeratenkorrektur?',
    'guide_what_p1'      => 'ACARS zeichnet deine Landerate automatisch beim Aufsetzen auf. Manchmal schlägt das fehl – wenn der Simulator genau beim Touchdown abstürzt, die Internetverbindung abbricht oder ACARS einen Software-Fehler hat. Das Ergebnis ist ein offensichtlich falscher Wert wie <strong>0 ft/min</strong>.',
    'guide_what_p2'      => 'Dieses Modul gibt dir die Möglichkeit, eine Korrektur formal zu beantragen. Du erklärst was passiert ist, kannst optional einen Screenshot anhängen, und ein Admin entscheidet. Bei Genehmigung wird dein PIREP automatisch aktualisiert.',
    'guide_what_note'    => 'Nur akzeptierte PIREPs können korrigiert werden. Entwürfe oder abgelehnte PIREPs sind nicht berechtigt.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: LANDING RATES
    // ═══════════════════════════════════════════════════════════
    'guide_rates_title'  => 'Landeraten-Referenz',
    'guide_rates_intro'  => 'Nutze diese Übersicht wenn du deinen Korrekturwert eingibst:',
    'guide_rates_smooth' => 'Sanft',
    'guide_rates_smooth_range' => '-50 bis -250 ft/min',
    'guide_rates_smooth_desc'  => 'Ausgezeichnete Landung',
    'guide_rates_normal' => 'Normal',
    'guide_rates_normal_range' => '-250 bis -600 ft/min',
    'guide_rates_normal_desc'  => 'Akzeptabel',
    'guide_rates_hard'   => 'Hart',
    'guide_rates_hard_range'   => 'unter -600 ft/min',
    'guide_rates_hard_desc'    => 'Harte Landung',
    'guide_rates_implausible'  => 'Unplausibel',
    'guide_rates_implausible_range' => '0 oder über -20 ft/min',
    'guide_rates_implausible_desc'  => 'Wahrscheinlich Fehler',
    'guide_rates_warn'   => 'Nur einreichen wenn du echten Nachweis hast. Dies dient zur Korrektur technischer Fehler – nicht zur Statistikverbesserung.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: SUBMIT REQUEST
    // ═══════════════════════════════════════════════════════════
    'guide_submit_title' => 'Wie stelle ich einen Antrag?',
    'guide_submit_step1' => 'Finde deinen Flug unter "Meine Flüge" oder "Unplausibel"',
    'guide_submit_step2' => 'Klicke "Korrektur beantragen" neben dem Flug',
    'guide_submit_step3' => 'Gib die korrekte Landerate ein, an die du dich erinnerst',
    'guide_submit_step4' => 'Erkläre warum der aufgezeichnete Wert falsch ist',
    'guide_submit_step5' => 'Optional einen Screenshot als Beweis anhängen',
    'guide_submit_step6' => 'Absenden und auf Admin-Prüfung warten',
    'guide_submit_tip'   => 'Screenshots von Flight-Trackern oder Replay-Tools helfen Admins bei der Prüfung.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: REQUEST STATUS
    // ═══════════════════════════════════════════════════════════
    'guide_status_title'        => 'Antragsstatus erklärt',
    'guide_status_pending'      => 'Ausstehend',
    'guide_status_pending_desc' => 'Wartet auf Admin-Prüfung.',
    'guide_status_approved'     => 'Genehmigt',
    'guide_status_approved_desc'=> 'Dein PIREP wurde mit dem korrigierten Wert aktualisiert.',
    'guide_status_rejected'     => 'Abgelehnt',
    'guide_status_rejected_desc'=> 'Antrag abgelehnt. Begründung in deinen Antragsdetails.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - SECTION: FAQ
    // ═══════════════════════════════════════════════════════════
    'guide_faq_title' => 'Häufig gestellte Fragen',
    'guide_faq_q1'    => 'Kann ich für jede Landung eine Korrektur beantragen?',
    'guide_faq_a1'    => 'Nein. Nur für offensichtlich falsche Werte (0 ft/min, positiv). Nicht zur Verbesserung normaler Landungen.',
    'guide_faq_q2'    => 'Welchen Nachweis brauche ich?',
    'guide_faq_a2'    => 'Idealerweise einen Screenshot von deinem Flight-Tracker oder Replay-Tool. Falls nicht verfügbar, erkläre klar was passiert ist.',
    'guide_faq_q3'    => 'Wie lange dauert die Prüfung?',
    'guide_faq_a3'    => 'Normalerweise ein paar Tage. Du wirst per E-Mail benachrichtigt falls aktiviert.',
    'guide_faq_q4'    => 'Kann ich nach einer Ablehnung erneut einreichen?',
    'guide_faq_a4'    => 'Ja, mit neuen Beweisen. Nutze den "Erneut beantragen" Button.',

    // ═══════════════════════════════════════════════════════════
    // GUIDE - ADMIN SECTIONS
    // ═══════════════════════════════════════════════════════════
    'guide_admin_only' => 'Admin-Bereich',
    
    // Admin Tabs
    'guide_admintabs_title' => 'Admin-Panel Navigation',
    'guide_admintabs_intro' => 'Der Admin-Bereich hat diese Tabs:',
    'guide_admintabs_requests'      => 'Offene Anträge',
    'guide_admintabs_requests_desc' => 'Pilotenanträge zur Prüfung',
    'guide_admintabs_history'       => 'Verlauf',
    'guide_admintabs_history_desc'  => 'Archiv bearbeiteter Anträge',
    'guide_admintabs_implausible'   => 'Unplausible PIREPs',
    'guide_admintabs_implausible_desc' => 'PIREPs mit verdächtigen Werten',
    'guide_admintabs_notifications' => 'Benachrichtigungen',
    'guide_admintabs_notifications_desc' => 'E-Mail-Vorlagen & Empfänger',
    'guide_admintabs_appearance'    => 'Darstellung',
    'guide_admintabs_appearance_desc' => 'Glass/Solid & Farben',

    // Review Process
    'guide_review_title'  => 'Anträge prüfen',
    'guide_review_intro'  => 'Bei der Prüfung beachten:',
    'guide_review_check1' => 'Ist der Originalwert eindeutig falsch?',
    'guide_review_check2' => 'Ist die Erklärung nachvollziehbar?',
    'guide_review_check3' => 'Unterstützt der Beweis die Behauptung?',
    'guide_review_check4' => 'Ist der beantragte Wert realistisch?',
    'guide_review_tip'    => 'Im Zweifel erst nach mehr Infos fragen bevor ablehnen.',

    // Direct Fix
    'guide_direct_title'  => 'Direktkorrektur',
    'guide_direct_intro'  => 'Ein PIREP ohne Pilotenantrag korrigieren – nützlich bei offensichtlichen Fehlern.',
    'guide_direct_step1'  => 'Gehe zu "Unplausible PIREPs"',
    'guide_direct_step2'  => 'Finde den betroffenen Flug',
    'guide_direct_step3'  => 'Klicke "Fix" und gib korrekten Wert ein',
    'guide_direct_step4'  => 'PIREP wird sofort aktualisiert',
    'guide_direct_note'   => 'Direktkorrekturen werden protokolliert. Pilot sieht korrigierten Wert.',

    // Notifications
    'guide_notify_title'  => 'E-Mail-Benachrichtigungen',
    'guide_notify_intro'  => 'Benachrichtigungen können gesendet werden für:',
    'guide_notify_event1' => 'Neue Anträge (an Admins)',
    'guide_notify_event2' => 'Genehmigt (an Pilot)',
    'guide_notify_event3' => 'Abgelehnt (an Pilot)',
    'guide_notify_setup'  => 'Konfigurieren im "Benachrichtigungen" Tab.',

    // Nav Links
    'guide_navlinks_title'     => 'Frontend-Navigation',
    'guide_navlinks_intro'     => 'Zur Theme-Navigation hinzufügen:',
    'guide_navlinks_tbl_who'   => 'Wer',
    'guide_navlinks_tbl_url'   => 'URL',
    'guide_navlinks_tbl_desc'  => 'Beschreibung',
    'guide_navlinks_pilot'     => 'Pilot',
    'guide_navlinks_pilot_desc'=> 'Piloten-Dashboard',
    'guide_navlinks_admin'     => 'Admin',
    'guide_navlinks_admin_desc'=> 'Admin-Panel',
    'guide_navlinks_admin_imp' => 'Unplausible + Direktkorrektur',
];
