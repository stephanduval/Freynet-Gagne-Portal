/**
 * plugins/webfontloader.js
 *
 * webfontloader documentation: https://github.com/typekit/webfontloader
 */

export async function loadFonts() {
  // Custom fonts are loaded via CSS @font-face declarations
  // No need to load from Google Fonts anymore
  console.log('Custom fonts loaded via CSS')
}

export default function () {
  loadFonts()
}