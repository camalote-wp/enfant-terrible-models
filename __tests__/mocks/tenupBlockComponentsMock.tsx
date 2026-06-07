// __tests__/mocks/tenupBlockComponentsMock.ts
export const ContentSearch = ({ onSelectItem, placeholder }: any) => (
  <div data-testid="content-search">
    <input placeholder={placeholder} />
    <button
      data-testid="select-mock-post"
      onClick={() =>
        onSelectItem({ 
          id: 123,
          title: 'Test',
          url: 'test',
          type: 'post',
          subtype: 'post'
        })
      }
    >
      Select Mock Post
    </button>
  </div>
);

export const ContentPicker = ({ onPickChange }: any) => (
  <div data-testid="content-picker">
    <button data-testid="pick-action" onClick={() => onPickChange([{ id: 42, type: 'page' }])}>Pick</button>
    <button data-testid="clear-action" onClick={() => onPickChange([])}>Clear</button>
  </div>
);

export const PostContext = ({ children, postId, postType }: any) => (
  <div data-testid="post-context" data-id={postId} data-type={postType}>{children}</div>
);

// FIX: Make the image look at the global dynamic mock reference
export const PostFeaturedImage = ({ className }: any) => {
  const currentPost = (global as any)._mockActivePost || {};
  
  // Choose class based on what the test returns for featured_media_url
  const fallbackClass = currentPost.featured_media_url === null 
    ? 'block-editor-media-placeholder' 
    : 'has-image';

  return <div data-testid="featured-image" className={`${className} ${fallbackClass}`} />;
};

export const PostTitle = ({ className }: any) => <div data-testid="post-title" className={className} />;
export const PostDate = ({ className }: any) => <div data-testid="post-date" className={className} />;

// FIX: Make the author check for a custom mock state if it exists
export const PostAuthor = ({ children, className }: any) => {
  const currentPost = (global as any)._mockActivePost || {};
  const mockAuthor = currentPost._customAuthor || { name: 'Author Name', link: 'http://link.test' };

  return (
    <div data-testid="post-author" className={className}>
      {typeof children === 'function' ? children(mockAuthor) : children}
    </div>
  );
};

export const usePost = () => (global as any)._mockActivePost || null;