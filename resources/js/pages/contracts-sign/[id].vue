<script setup>
import { useTheme } from 'vuetify';

const theme = useTheme();
const route = useRoute('contracts-sign-id')
definePage({ meta: { navActiveLink: 'contracts' } })
</script>

<template>
  <div>
    <VCard class="d-flex justify-center align-center items-center" style="height:80vh;">
      Docusign here...
    </VCard>
  </div>
</template>
