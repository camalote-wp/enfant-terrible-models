const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const PLUGIN_ROOT = path.resolve('.');
const BUILD_DIR = path.resolve('release');
const TMP_DIR = path.resolve('tmp-plugin');

// Read plugin name/version from package.json
const packageJson = JSON.parse(fs.readFileSync(path.join(PLUGIN_ROOT, 'package.json')));
const PLUGIN_NAME = (packageJson.name || 'my-plugin').replace(/^@[^/]+\//, '');
const VERSION = packageJson.version || '1.0.0';

const ZIP_NAME = `${PLUGIN_NAME}-${VERSION}.zip`;
const ZIP_PATH = path.join(BUILD_DIR, ZIP_NAME);

console.log(`Packaging plugin "${PLUGIN_NAME}" version ${VERSION}...`);

// Prepare temp folder
if (fs.existsSync(TMP_DIR)) fs.rmSync(TMP_DIR, { recursive: true });
fs.mkdirSync(TMP_DIR, { recursive: true });
const zipFolder = path.join(TMP_DIR, PLUGIN_NAME);
fs.mkdirSync(zipFolder, { recursive: true });

// Files/directories to include
const ITEMS = ['dist', 'languages', 'src', 'vendor-scoped', 'plugin.php'];

// Recursive copy function with exclude
function copyRecursive(src, dest) {
  const stats = fs.statSync(src);
  if (stats.isDirectory()) {
    const baseName = path.basename(src);
    if (baseName === 'class-loader-cache') return; // skip this folder

    if (!fs.existsSync(dest)) fs.mkdirSync(dest, { recursive: true });

    fs.readdirSync(src).forEach(child => {
      copyRecursive(path.join(src, child), path.join(dest, child));
    });
  } else if (stats.isFile()) {
    fs.copyFileSync(src, dest);
  }
}

// Copy items into temp plugin folder
ITEMS.forEach(item => {
  const src = path.join(PLUGIN_ROOT, item);
  const dest = path.join(zipFolder, item);
  if (!fs.existsSync(src)) return;

  copyRecursive(src, dest);
});

// Create build folder if missing
if (!fs.existsSync(BUILD_DIR)) fs.mkdirSync(BUILD_DIR, { recursive: true });

// Create zip via system command
execSync(`cd "${TMP_DIR}" && zip -r "${ZIP_PATH}" "${PLUGIN_NAME}"`, { stdio: 'inherit' });

// Clean up temp folder
fs.rmSync(TMP_DIR, { recursive: true });

console.log(`Plugin packaged successfully: ${ZIP_PATH}`);
