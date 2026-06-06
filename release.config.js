const isDryRun = process.argv.includes("--dry-run");

const plugins = [
	"@semantic-release/commit-analyzer",
	"@semantic-release/release-notes-generator",
	"@semantic-release/changelog",
	[
		"@semantic-release/exec",
		{
			prepareCmd: "node bin/update-version.js ${nextRelease.version}",
			publishCmd: "npm run zip",
		},
	],
	[
		"@semantic-release/git",
		{
			assets: ["CHANGELOG.md", "package.json", "plugin.php"],
			message:
				"chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}",
		},
	],
];

// Only add GitHub plugin if NOT in dry-run mode
if (!isDryRun) {
	plugins.push([
		"@semantic-release/github",
		{
			assets: [{ path: "release/*.zip" }],
			successComment: false,
			failComment: false,
			releasedLabels: false,
		},
	]);
}

module.exports = {
	branches: ["main"],
	plugins,
};
