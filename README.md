# R3D ATUM Override (plg_r3datumoverride)

Backend-only system plugin for Joomla 6 to safely override typography and spacing
in the ATUM administrator template **without breaking color modes**.

---

## 🎯 Purpose

Joomla 6 (ATUM + Bootstrap 5.3) uses CSS variables and color modes extensively.
Direct template overrides or global CSS (`html`, `body`, `:root`) can easily break
the backend color scheme.

This plugin provides a **clean, update-safe, backend-only solution** to:

- adjust font sizes
- adjust spacing
- fine-tune backend typography
- keep ATUM colors and light/dark modes fully intact

---

## ✅ Key Features

- ✔ **Backend only** (administrator client)
- ✔ Joomla 6.0+ compatible
- ✔ Uses safe CSS scoping (`#atum`)
- ✔ Does **not** override color variables
- ✔ Update-safe (no core hacks)
- ✔ Clean Git-based workflow

---

## 🚫 What this plugin does NOT do

- ❌ No frontend execution
- ❌ No frontend assets
- ❌ No color overrides
- ❌ No template hacks
- ❌ No direct modification of ATUM files

---

## 📂 Plugin Structure

plg_r3datumoverride/
├─ r3datumoverride.php
├─ r3datumoverride.xml
├─ src/
│ └─ Extension/
│ └─ R3datumoverride.php
├─ media/
│ └─ css/
│ └─ atum-override.css
├─ language/
│ ├─ de-DE/
│ └─ en-GB/
└─ README.md

---

## 🧠 How it works

- The plugin runs **only in the administrator client**
- CSS is injected during `onBeforeCompileHead`
- Styles are loaded from: /media/plg_system_r3datumoverride/css/atum-override.css
- All CSS is scoped to `#atum`
- No global selectors (`html`, `body`, `:root`) are used

---

## 🖌 CSS Rules (Important)

The CSS file **must follow these rules**:

- ✅ Always scope to `#atum`
- ❌ Never style `html` or `body`
- ❌ Never override Bootstrap / ATUM color variables
- ✅ Typography and spacing only

Example:

```css
#atum {
  font-size: 0.95rem;
}

#atum .table {
  font-size: 0.875rem;
}

}
```

---

## 📦 Installation
 - Download the plugin ZIP
 - Joomla Backend → Extensions → Install
 - Upload ZIP
 - Enable plugin:  System → R3D ATUM Override

---

## 🧩 Compatibility
 - Joomla: 6.0+
 - Administrator template: ATUM
 - PHP: 8.2+

---

## 👤 Author
Richard Dvořák
R3D Internet Dienstleistungen
https://r3d.de

---

## 📄 License
GNU General Public License v2 or later
See LICENSE.txt
