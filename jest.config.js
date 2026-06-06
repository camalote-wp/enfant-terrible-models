// jest.config.js
module.exports = {
	preset: "ts-jest",
	testEnvironment: "jsdom",
	setupFilesAfterEnv: ["<rootDir>/__tests__/bootstrap.ts"],
	testPathIgnorePatterns: [
		"/node_modules/",
		"/__tests__/bootstrap.ts",
		"/__tests__/mocks/",
	],
	moduleNameMapper: {
		"^react-tabs$": "<rootDir>/__tests__/mocks/reactTabsMock.tsx",
		"^@10up/block-components$":
			"<rootDir>/__tests__/mocks/tenupBlockComponentsMock.tsx",
		"\\.(css|scss)$": "<rootDir>/__tests__/mocks/styleMock.tsx",
		"^@wordpress/components$": "<rootDir>/__tests__/mocks/wpComponentsMock.tsx",
		"^@wordpress/block-editor$": "<rootDir>/__tests__/mocks/wpBlockEditorMock.tsx",
	},
	transform: {
		"^.+\\.[jt]sx?$": [
			"ts-jest",
			{
				tsconfig: "tsconfig.watch.json",
			},
		],
	},
};
