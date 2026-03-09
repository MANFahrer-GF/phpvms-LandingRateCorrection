# LandingRateCorrection Module – Translation Guide

This guide explains how to add or edit translations for the LandingRateCorrection module.

---

## 📁 File Structure

All language files are located in:
```
modules/LandingRateCorrection/Resources/lang/
├── de/lrc.php    ← German (100% translated)
├── en/lrc.php    ← English (100% translated, master file)
├── es/lrc.php    ← Spanish (English fallback, ready to translate)
├── fr/lrc.php    ← French (English fallback, ready to translate)
├── it/lrc.php    ← Italian (English fallback, ready to translate)
├── ja/lrc.php    ← Japanese (English fallback, ready to translate)
├── pt-br/lrc.php ← Portuguese Brazilian (English fallback, ready to translate)
├── pt-pt/lrc.php ← Portuguese (English fallback, ready to translate)
└── tr/lrc.php    ← Turkish (English fallback, ready to translate)
```

---

## ✏️ How to Translate an Existing Language

1. **Open the language file**  
   Navigate to `Resources/lang/XX/lrc.php` where `XX` is the language code (e.g., `fr` for French)

2. **Translate the values**  
   Each line looks like this:
   ```php
   'key' => 'English text here',
   ```
   Change only the text inside the quotes on the right side:
   ```php
   'key' => 'Texte français ici',
   ```

3. **Save the file**  
   Laravel will automatically use your translation.

### Example: Translating to French

**Before (English fallback):**
```php
'title'      => 'Landing Rate Corrections',
'subtitle'   => 'Request a correction for an incorrectly recorded landing rate.',
'guide_title'=> 'Landing Rate Corrections – Guide',
```

**After (French):**
```php
'title'      => 'Corrections de Taux d\'Atterrissage',
'subtitle'   => 'Demandez une correction pour un taux d\'atterrissage mal enregistré.',
'guide_title'=> 'Corrections de Taux d\'Atterrissage – Guide',
```

---

## ➕ How to Add a New Language

### Step 1: Create the folder
Create a new folder with the language code:
```
modules/LandingRateCorrection/Resources/lang/XX/
```
Replace `XX` with the ISO language code (e.g., `nl` for Dutch, `pl` for Polish).

### Step 2: Copy the English file
Copy the English master file as your template:
```
FROM: Resources/lang/en/lrc.php
TO:   Resources/lang/XX/lrc.php
```

### Step 3: Translate
Open your new `lrc.php` file and translate all values.

### Step 4: Done!
Laravel automatically detects the language based on `app()->getLocale()`. No additional configuration needed.

---

## 📋 Translation Keys Overview

The language file contains **187 lines** with these sections:

### General UI (~35 keys)
Basic interface elements like tabs, buttons, status labels.
```php
'title'       => 'Landing Rate Corrections',
'tab_flights' => 'My Flights',
'tab_imp'     => 'Implausible',
'btn_fix'     => 'Request Fix',
'pending'     => 'Pending',
'approved'    => 'Approved',
// ...
```

### Guide Section (~108 keys)
All text for the built-in guide/handbook.

| Section | Keys | Description |
|---------|------|-------------|
| `guide_nav_*` | 10 | Navigation card titles |
| `guide_what_*` | 4 | "What is LRC" section |
| `guide_rates_*` | 13 | Landing rate reference |
| `guide_submit_*` | 8 | How to submit a request |
| `guide_status_*` | 7 | Status explanations |
| `guide_faq_*` | 9 | FAQ questions & answers |
| `guide_admintabs_*` | 12 | Admin tab descriptions |
| `guide_review_*` | 7 | Review process |
| `guide_direct_*` | 6 | Direct correction |
| `guide_notify_*` | 6 | Notifications |
| `guide_navlinks_*` | 10 | Navigation links |

---

## ⚠️ Important Notes

### Keep the keys unchanged
Only translate the **values**, never change the keys:
```php
// ✅ Correct
'guide_title' => 'Mon Titre Traduit',

// ❌ Wrong - don't change the key!
'mon_titre' => 'Mon Titre Traduit',
```

### Escape single quotes
If your translation contains a single quote `'`, escape it with a backslash:
```php
// ✅ Correct
'guide_faq_a2' => 'If you don\'t have proof, explain clearly.',

// ❌ Wrong - will cause PHP error
'guide_faq_a2' => 'If you don't have proof, explain clearly.',
```

### HTML is allowed in some fields
Some fields support HTML (like `<strong>`):
```php
'guide_what_p1' => 'The result is a clearly wrong value like <strong>0 ft/min</strong>.',
```

### Missing translations fall back to English
If a key is missing or the language file doesn't exist, Laravel automatically falls back to English.

---

## 🔍 Testing Your Translation

1. **Change phpVMS language setting**  
   Go to Admin → Settings → General and set the language, or the user can change it in their profile.

2. **Clear the cache** (if translations don't appear)
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

3. **Visit the LRC page**  
   Navigate to `/lrc` and check if your translations appear.

---

## 📞 Support

If you need help or want to contribute a translation, contact the module author or submit a pull request with your language file.

---

*Last updated: March 2025*
