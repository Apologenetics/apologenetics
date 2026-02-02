<script lang="ts" setup>
import { ref } from 'vue';
import { Religion, SimplePaginated } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Deferred, Head, Link, usePage } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import ReligionController from '@/actions/App/Http/Controllers/ReligionController';
import ReligionFormModal from '@/components/ReligionFormModal.vue';
import { LoaderCircle } from 'lucide-vue-next';

const authenticated: boolean = usePage().props.auth.user !== null;

const showPending = ref<boolean>(false)

defineProps<{
    religions?: SimplePaginated<Religion>;
}>();

const selectedReligion = ref<Religion | undefined>(undefined);
</script>
<template>
    <Head title="Religions" />

    <AppLayout>
        <div class="flex flex-col p-8">
            <Heading title="Religions" />

            <div v-if="authenticated" class="flex flex-row items-center gap-4 mt-6">
                <Deferred data="religions">
                    <ReligionFormModal
                        :religion="selectedReligion"
                        :religions="religions?.data ?? []"
                    >
                        <template #trigger>
                            <Button>+ Add Religion</Button>
                        </template>
                    </ReligionFormModal>
                    <template #fallback>
                        <LoaderCircle class="h-8 w-8 animate-spin" />
                    </template>
                </Deferred>
                <Button @click="showPending = !showPending">Show Pending</Button>
            </div>

            <Deferred
                data="religions"
            >
                <div class="mt-6 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <template v-if="religions !== undefined">
                        <Link
                            v-for="religion in religions.data"
                            :key="religion.id"
                            :href="ReligionController.show(religion.id).url"
                        >
                            <div class="h-full items-center justify-center flex flex-col gap-2 p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border hover:bg-muted/50">
                                <h3 class="text-xl font-semibold tracking-tight">{{ religion.name }}</h3>

                                <p class="text-sm text-muted-foreground">{{ religion.description }}</p>
                            </div>
                        </Link>
                    </template>
                </div>

                <template #fallback>
                    <LoaderCircle class="h-8 w-8 animate-spin" />
                </template>
            </Deferred>
        </div>
    </AppLayout>
</template>
