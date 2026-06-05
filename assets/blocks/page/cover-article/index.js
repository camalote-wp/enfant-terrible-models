import { registerBlockType } from "@wordpress/blocks";
import { addFilter } from "@wordpress/hooks";

import { BlockEdit } from "./edit";
import metadata from "./block.json";
import "./style.scss";

registerBlockType(metadata, {
	edit: BlockEdit,
	save: () => null,
});