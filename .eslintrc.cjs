module.exports = {
  root: true,
  parserOptions: {
    parser: '@typescript-eslint/parser',
    project: 'tsconfig.json',
  },
  plugins: ['import', '@typescript-eslint', 'prettier'],
  extends: [
    'plugin:@typescript-eslint/recommended',
    'airbnb',
    'airbnb-typescript',
    'prettier',
  ],
  rules: {
    'prettier/prettier': ['error'],
    'import/prefer-default-export': 0,
    'class-methods-use-this': 0,
    'no-restricted-syntax': 0,
    'no-plusplus': 0,
    'default-case': 0,
    'no-nested-ternary': 0,
    'react/jsx-props-no-spreading': 0,
    'react/require-default-props': 0,
    '@typescript-eslint/no-unused-expressions': [
      'error',
      {
        allowTernary: true,
      },
    ],
    'no-param-reassign': [
      'error',
      {
        props: false,
      },
    ],
    '@typescript-eslint/ban-ts-comment': 0,
    '@typescript-eslint/no-non-null-assertion': 0,
    'import/no-cycle': 0,
    '@typescript-eslint/lines-between-class-members': [
      1,
      {
        exceptAfterSingleLine: true,
      },
    ],
  },
  env: {
    browser: true,
  },
  settings: {
    'import/parsers': {
      '@typescript-eslint/parser': ['.ts', '.tsx'],
    },
    'import/resolver': {
      typescript: {
        alwaysTryTypes: true, // always try to resolve types under `<root>@types` directory even it doesn't contain any source code, like `@types/unist`
        // use an array of glob patterns
        project: ['tsconfig.json'],
      },
    },
  },
};
