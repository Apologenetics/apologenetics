<script lang="ts" setup>
import { Doctrine, Religion } from '@/types';
import Heading from '@/components/Heading.vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps<{
    religion: Religion,
    doctrines?: Doctrine[]
}>()
</script>
<template>
    <Head :title="`${religion.name}'s Doctrines`" />
    <AppLayout>
        <div class="size-full p-8">
            <Heading :title="`${religion.name} Doctrines`" />
        </div>
    </AppLayout>
</template>
