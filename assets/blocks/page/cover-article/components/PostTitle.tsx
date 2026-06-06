import { usePost } from '@10up/block-components';
import { useEntityProp } from '@wordpress/core-data';

interface PostTitleProps {
    tagName?: React.ElementType;
    className?: string;
    href?: string;
    newTab?: boolean;
}

export const PostTitle = ({ tagName: TagName = 'h1', href, newTab = false, ...rest }: PostTitleProps) => {
    const { postId, postType } = usePost();
    const [rawTitle = '', , fullTitle] = useEntityProp(
        'postType',
        postType,
        'title',
        postId as undefined | string,
    );

    const title = fullTitle?.rendered ?? rawTitle;
    const targetProps = newTab ? { target: '_blank', rel: 'noopener noreferrer' } : {};

    return (
        <TagName {...rest}>
            {href ? <a href={href} {...targetProps}>{title}</a> : title}
        </TagName>
    );
};