const webpackConfigs = require("10up-toolkit/config/webpack.config");

const configsArray = Array.isArray(webpackConfigs)
	? webpackConfigs
	: [webpackConfigs];

const mainConfig = configsArray[0];

// Centralize HMR/DevServer settings
const HMR_SETTINGS = {
	host: "0.0.0.0",
	port: 5011,
	sockHost: "example.ddev.site",
	sockPort: 5011,
	protocol: "wss",
};

const scssRule = mainConfig.module.rules.find(
	(r) => r.test instanceof RegExp && r.test.source.includes("sc|sa"),
);

const sassLoader = scssRule.use.find((u) => u.loader?.includes("sass-loader"));

if (sassLoader) {
	// Force modern Dart Sass implementation
	sassLoader.options = {
		...sassLoader.options,
		api: "modern", // this is Dart Sass
	};
}

// Patch devServer
if (mainConfig.devServer) {
	mainConfig.devServer = {
		...mainConfig.devServer,
		host: HMR_SETTINGS.host,
		port: HMR_SETTINGS.port,
		client: {
			...mainConfig.devServer.client,
			webSocketURL: {
				hostname: HMR_SETTINGS.sockHost,
				port: HMR_SETTINGS.sockPort,
				protocol: HMR_SETTINGS.protocol,
			},
		},
	};
}

// Patch ReactRefreshPlugin
if (mainConfig.plugins) {
	mainConfig.plugins = mainConfig.plugins.map((plugin) => {
		if (plugin.constructor.name === "ReactRefreshPlugin") {
			return new plugin.constructor({
				...plugin.options,
				overlay: {
					...plugin.options.overlay,
					sockHost: HMR_SETTINGS.sockHost,
					sockProtocol: HMR_SETTINGS.protocol,
					sockPort: HMR_SETTINGS.sockPort,
				},
			});
		}
		return plugin;
	});
}

module.exports = configsArray;