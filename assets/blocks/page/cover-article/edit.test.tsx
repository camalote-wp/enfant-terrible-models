import { fireEvent, render, screen } from '@testing-library/react';

import { BlockEdit } from './edit';

// Mock the translation package locally
jest.mock("@wordpress/i18n", () => ({
  __: (val: string) => val,
}));

// Dynamic inline mocks for the core selection framework
const mockGetEntityRecord = jest.fn();
jest.mock("@wordpress/data", () => ({
  useSelect: (callback: any) => callback((storeName: string) => ({ getEntityRecord: mockGetEntityRecord })),
}));

jest.mock("@wordpress/core-data", () => ({
  store: "core/core-data",
  useEntityProp: jest.fn().mockReturnValue([null, jest.fn(), false]),
}));

describe("BlockEdit", () => {
  const setAttributes = jest.fn();

  // Helper to set up the data and register it to the global mock state safely
  const setupActivePost = (postData: any) => {
    mockGetEntityRecord.mockReturnValue(postData);
    (global as any)._mockActivePost = postData;
  };

  beforeEach(() => {
    jest.clearAllMocks();
    (global as any)._mockActivePost = null;
  });

  // --- Core Lifecycle States ---

  test("shows placeholder state when no post is configured", () => {
    render(<BlockEdit attributes={{ selectedPost: null }} setAttributes={setAttributes} />);
    expect(screen.getByTestId("placeholder")).toBeInTheDocument();
  });

  test("shows spinner state during background resolution data fetching", () => {
    setupActivePost(null);
    render(<BlockEdit attributes={{ selectedPost: { id: 5 } }} setAttributes={setAttributes} />);
    expect(screen.getByTestId("spinner")).toBeInTheDocument();
  });

  test("renders layout preview node upon successful resolution", () => {
    setupActivePost({ id: 5, link: "http://site.test/my-post" });
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    expect(screen.getByTestId("post-context")).toHaveAttribute("data-id", "5");
  });

  // --- Featured Image Branches ---

  test("renders the image element when the post has a featured image", () => {
    setupActivePost({ id: 5, link: "http://site.test/my-post", featured_media: 123 });
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    const imageElement = screen.getByTestId("featured-image");
    expect(imageElement).toBeInTheDocument();
  });

  test("renders the placeholder element when the post lacks a featured image", () => {
    setupActivePost({ id: 5, link: "http://site.test/my-post", featured_media: 0 });
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    const imageElement = screen.queryByTestId("featured-image");
    expect(imageElement).not.toBeInTheDocument();
  });

  // --- Author Element Branches ---

  test("renders an anchor element when the author context contains a link", () => {
    setupActivePost({ id: 5, link: "http://site.test/my-post" });
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    const authorContainer = screen.getByTestId("post-author");
    expect(authorContainer.querySelector("a")).toBeInTheDocument();
    expect(authorContainer.querySelector("a")).toHaveAttribute("href", "http://link.test");
  });

  test("renders a plain text span element when the author context lacks a link", () => {
    setupActivePost({ 
      id: 5, 
      link: "http://site.test/my-post",
      _customAuthor: { name: "Anonymous Author", link: null } 
    });
    
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    const authorContainer = screen.getByTestId("post-author");
    expect(authorContainer.querySelector("a")).not.toBeInTheDocument();
    expect(authorContainer.querySelector("span")).toBeInTheDocument();
    expect(screen.getByText("Anonymous Author")).toBeInTheDocument();
  });

  // --- Selection Interactions ---

  test("dispatches accurate state modifications via content changes", () => {
    render(<BlockEdit attributes={{ selectedPost: null }} setAttributes={setAttributes} />);

    fireEvent.click(screen.getByTestId("pick-action"));
    expect(setAttributes).toHaveBeenCalledWith({ selectedPost: { id: 42, type: "page" } });
  });

  test("dispatches null to selectedPost when selection is explicitly cleared", () => {
    setupActivePost({ id: 5 });
    render(<BlockEdit attributes={{ selectedPost: { id: 5, type: "post" } }} setAttributes={setAttributes} />);

    fireEvent.click(screen.getByTestId("clear-action"));
    expect(setAttributes).toHaveBeenCalledWith({ selectedPost: null });
  });
});