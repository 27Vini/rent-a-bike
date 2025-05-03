// vitest.config.ts

import { defineConfig } from 'vitest/config'

export default defineConfig({
    test: {
        include: [ 'app/test/**/*.spec.js' ],
      },
})
