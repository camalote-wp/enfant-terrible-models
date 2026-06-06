import { registerBlockType } from "@wordpress/blocks";

import { BlockEdit } from "./edit";
import metadata from "./block.json";
import "./style.scss";

registerBlockType(metadata, {
	edit: BlockEdit,
	save: () => null,
});