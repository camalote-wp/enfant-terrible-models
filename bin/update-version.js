#!/usr/bin/env node
const fs = require('fs');
const path = require('path');

const newVersion = process.argv[2];

if (!newVersion) {
  console.error('Usage: node update-version.js <new-version>');
  process.exit(1);
}

// Update main PHP plugin file
const pluginFile = path.resolve(__dirname, '../plugin.php');
let phpContent = fs.readFileSync(pluginFile, 'utf8');

// Update header version
phpContent = phpContent.replace(
  /(\* Version:\s*)[^\r\n]*/,
  `$1${newVersion}`
);

// Update PHP constant
phpContent = phpContent.replace(
  /(define\s*\(\s*['"]CAMALOTE_WP_ZORZAL_MODELS_VERSION['"],\s*['"])[^'"]*(['"])/,
  `$1${newVersion}$2`
);

fs.writeFileSync(pluginFile, phpContent);

// Update package.json
const packageJsonPath = path.resolve(__dirname, '../package.json');
const packageData = JSON.parse(fs.readFileSync(packageJsonPath, 'utf8'));
packageData.version = newVersion;
fs.writeFileSync(packageJsonPath, JSON.stringify(packageData, null, 2) + '\n');

console.log(`Updated version to ${newVersion}`);