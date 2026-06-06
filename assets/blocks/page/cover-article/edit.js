import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, Placeholder, Spinner } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { store as coreStore } from "@wordpress/core-data";
import {
	ContentPicker,
	PostContext,
	PostFeaturedImage,
	PostTitle,
	PostAuthor,
	PostDate,
} from "@10up/block-components";
import "./editor.scss";

const PostPreview = ({ postLink }) => (
	<div className="wp-block-camalote-wp-cover-article__inner">
		<div className="wp-block-camalote-wp-cover-article__media">
			<a
				href={postLink}
				className="wp-block-camalote-wp-cover-article__image-link"
			>
				<PostFeaturedImage className="wp-block-camalote-wp-cover-article__image" />
			</a>
		</div>
		<div className="wp-block-camalote-wp-cover-article__content">
			<a
				href={postLink}
				className="wp-block-camalote-wp-cover-article__title-link"
			>
				<PostTitle
					tagName="h1"
					className="wp-block-camalote-wp-cover-article__title"
				/>
			</a>
			<div className="wp-block-camalote-wp-cover-article__meta">
				<PostAuthor className="wp-block-camalote-wp-cover-article__author">
					{(author) =>
						author?.link ? (
							<a
								href={author.link}
								className="wp-block-camalote-wp-cover-article__author-link"
							>
								<span className="wp-block-camalote-wp-cover-article__author-name">
									{author.name}
								</span>
							</a>
						) : (
							<span className="wp-block-camalote-wp-cover-article__author-name">
								{author.name}
							</span>
						)
					}
				</PostAuthor>
				<PostDate className="wp-block-camalote-wp-cover-article__date" />
			</div>
		</div>
	</div>
);

export const BlockEdit = ({ attributes, setAttributes }) => {
	const { selectedPost } = attributes;

	const pickedItems = selectedPost?.id
		? [{ id: selectedPost.id, type: selectedPost.type ?? "post" }]
		: [];

	const post = useSelect(
		(select) => {
			if (!selectedPost?.id) return null;
			return select(coreStore).getEntityRecord(
				"postType",
				selectedPost.type ?? "post",
				selectedPost.id,
			);
		},
		[selectedPost],
	);

	const handlePickChange = (picked) => {
		if (!picked?.length) {
			setAttributes({ selectedPost: null });
			return;
		}
		const { id, type } = picked[0];
		setAttributes({ selectedPost: { id, type } });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Post selection", "camalote-wp-zorzal-models")}>
					<ContentPicker
						label={__("Select a post", "camalote-wp-zorzal-models")}
						mode="post"
						contentTypes={["post"]}
						maxContentItems={1}
						content={pickedItems}
						onPickChange={handlePickChange}
						singlePickedLabel={__(
							"You have selected the following item:",
							"camalote-wp-zorzal-models",
						)}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className: "alignwide" })}>
				{!selectedPost?.id && (
					<Placeholder
						icon="admin-appearance"
						label={__("Camalote WP - Cover Article", "camalote-wp-zorzal-models")}
						instructions={__(
							"Select a post in the sidebar.",
							"camalote-wp-zorzal-models",
						)}
					/>
				)}
				{selectedPost?.id && !post && <Spinner />}
				{post && (
					<PostContext
						postId={selectedPost.id}
						postType={selectedPost.type ?? "post"}
						isEditable={false}
					>
						<PostPreview postLink={post.link ?? null} />
					</PostContext>
				)}
			</div>
		</>
	);
};
