// __tests__/bootstrap.ts
import '@testing-library/jest-dom';

import * as wpComponents from '@wordpress/components';
import * as wpElement from '@wordpress/element';

// Attach WordPress globals
(global as any).window.wp = {
  i18n: {
    __: (text: string) => text,
    setLocaleData: () => {},
  },
  element: wpElement, // full module, not destructured
  components: wpComponents,
};