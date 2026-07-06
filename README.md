# R3D ATUM Override

Current release: `1.0.16`.

Backend-only Joomla system plugin for adjusting typography and spacing in the ATUM administrator template without touching Joomla core files.

## Purpose

The plugin is meant for backend styling adjustments only. It keeps the administrator template intact and avoids frontend execution.

## Update Feed

The Joomla update server URL for this plugin is:

`https://extensions.r3d.de/phocadownload/plg_system_r3datumoverride.xml`

## Installation

1. Build the installable ZIP from `project.json`.
2. Install it in Joomla via `System -> Install -> Extensions`.
3. Enable `System - R3D ATUM Override`.

## Project Structure

- `01_src/plg_system_r3datumoverride/`: installable source tree
- `scripts/`: local build, verification, and publish wrappers
- `04_dist/`: generated ZIP artifacts
- `05_updates/`: generated update XML and release-plan artifacts

## Build

Use the shared toolchain from the project root:

```powershell
pwsh -File "D:\1DEV\_tools\04-build-extension.ps1"
```

Or use the local wrapper:

```powershell
pwsh -File ".\scripts\build-release-r3datumoverride.ps1"
```

## Publish

The publish wrapper runs the shared download and update-server scripts:

```powershell
pwsh -File ".\scripts\publish-r3datumoverride.ps1"
```

If plugin-specific Phoca category IDs are available in the ENV file, the wrapper prefers them over the generic language category IDs so the release lands in the PhocaDownload Plugins area.

## Notes

- The plugin runs only in the administrator client.
- Runtime CSS is loaded from `media/plg_system_r3datumoverride/css/atum-override.css`.
- The manifest and the PHP bootstrap are versioned with `1.0.16`.
