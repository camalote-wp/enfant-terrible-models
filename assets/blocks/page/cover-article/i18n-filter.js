import { addFilter } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";

/**
 * Intercept the 10up content picker translation domain.
 * This keeps the text exactly the same while routing it through your
 * own text domain so that string-extraction tools can index it for the .pot file.
 */
addFilter(
	"i18n.gettext_10up-block-components",
	"camalote-wp-zorzal-models/cover-article-load-more-override",
	(translation, text) => {
		if (text === "Load more") {
			// Text is UNCHANGED.
			// The __ call ensures this exact string is grabbed for your .pot file.
			return __("Load more", "camalote-wp-zorzal-models");
		}

		return translation;
	},
);
