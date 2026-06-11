import { PostAuthor, PostDate, PostFeaturedImage } from '@10up/block-components';
import { __ } from '@wordpress/i18n';

import { PostTitle } from './PostTitle';

interface PostPreviewProps {
	postLink: string;
	hasImage: boolean;
}

export const PostPreview = ({ postLink, hasImage }: PostPreviewProps) => {
	const imageClass = hasImage		? "wp-block-camalote-wp-cover-article__inner--has-image"
		: "wp-block-camalote-wp-cover-article__inner--no-image";
	const handleClick = (e: React.MouseEvent) => e.preventDefault();
	return (
		<div className={`wp-block-camalote-wp-cover-article__inner ${imageClass}`}>
				{/* Wrap the media container in a conditional check */}
				{hasImage && (
					<div className="wp-block-camalote-wp-cover-article__media">
						<a
							href={postLink}
							className="wp-block-camalote-wp-cover-article__image-link"
							onClick={handleClick}
						>
							<PostFeaturedImage className="wp-block-camalote-wp-cover-article__image" />
						</a>
					</div>
				)}
			<div className="wp-block-camalote-wp-cover-article__content">
					<PostTitle
						tagName="h1"
						className="wp-block-camalote-wp-cover-article__title wp-block-post-title"
						href={postLink}
						onClick={handleClick}
					/>
				<div className="wp-block-camalote-wp-cover-article__meta">
					<div className="wp-block-post-author-name has-large-font-size">
						<PostAuthor className="wp-block-camalote-wp-cover-article__author">
							{(author) =>
								author?.link ? (
									<a
										href={author.link}
										className="wp-block-post-author-name__link wp-block-camalote-wp-cover-article__author-link"
										onClick={handleClick}
									>
										{author.name}
									</a>
								) : (
									<span className="wp-block-camalote-wp-cover-article__author-name">
										{author.name}
									</span>
								)
							}
						</PostAuthor>
					</div>
					<div className="wp-block-post-date has-large-font-size">
						<PostDate className="wp-block-camalote-wp-cover-article__date" placeholder={__('No date', 'camalote-wp-zorzal-models')} />
					</div>
				</div>
			</div>
			<hr className="wp-block-separator has-alpha-channel-opacity"/>
		</div>
);
};