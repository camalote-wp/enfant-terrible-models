import { usePost } from '@10up/block-components';
import { useEntityProp } from '@wordpress/core-data';

interface PostTitleProps {
    tagName?: React.ElementType;
    className?: string;
    href?: string;
    newTab?: boolean;
    onClick?: React.MouseEventHandler<HTMLAnchorElement>; // <-- Just add this
}

export const PostTitle = ({ tagName: TagName = 'h1', href, newTab = false, onClick, ...rest }: PostTitleProps) => {
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
            {href ? <a href={href} onClick={onClick} {...targetProps}>{title}</a> : title}
        </TagName>
    );
};