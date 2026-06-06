// __tests__/mocks/wpIconsMock.tsx

export const image = 'svg-image-icon';
export const plus = 'svg-plus-icon';
export const trash = 'svg-trash-icon';

// If any component imports an icon that isn't explicitly defined above,
// this fallback object prevents runtime import crashes.
const wpIconsMock = {
  image,
  plus,
  trash,
};

export default wpIconsMock;