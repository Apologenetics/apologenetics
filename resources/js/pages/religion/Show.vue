<script lang="ts" setup>
import CorrelationFormModal from '@/components/CorrelationFormModal.vue';
import DoctrineFormModal from '@/components/DoctrineFormModal.vue';
import Heading from '@/components/Heading.vue';
import Item from '@/components/Item.vue';
import ReligionFormModal from '@/components/ReligionFormModal.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { convertToItemData } from '@/lib/item-utils';
import { Religion } from '@/types';
import { Deferred, Link, router } from '@inertiajs/vue3';
import { LoaderCircle, Plus } from 'lucide-vue-next';
import { defineProps } from 'vue';

const deleteDoctrine = async (id: number): Promise<void> => {
    console.log('deleting doctrine', id);
};

const editDoctrine = async (id: number): Promise<void> => {
    console.log('editing doctrine', id);
};

const handleDoctrineAdded = async (): Promise<void> => {
    router.reload({ preserveUrl: true })
}

defineProps<{
    religion?: Religion;
}>();
</script>
<template>
    <AppLayout>
        <Deferred data="religion">
            <template #fallback>
                <div class="flex size-full items-center justify-center">
                    <LoaderCircle class="h-8 w-8 animate-spin" />
                </div>
            </template>

            <div class="flex flex-col p-8" v-if="religion !== undefined">
                <Heading
                    :title="religion.name"
                    :description="religion.description ?? ''"
                />

                <div class="mt-8 flex flex-col gap-8">
                    <!-- Sub-groups -->
                    <div
                        v-if="
                            religion.descendants !== undefined &&
                            religion.descendants.length > 0
                        "
                        class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <Heading
                                title="Sub-Groups"
                                description="Break-off or sects of this religion"
                            />
                            <ReligionFormModal :religion-id="religion.id">
                                <template #trigger>
                                    <Button size="sm">
                                        <Plus class="mr-2 h-4 w-4" />
                                        Add Sub-Group
                                    </Button>
                                </template>
                            </ReligionFormModal>
                        </div>

                        <template
                            v-if="
                                religion.doctrines !== undefined &&
                                religion.doctrines?.length > 0
                            "
                        >
                            <Item
                                v-for="doctrine in religion.doctrines"
                                :key="`doctrine-${doctrine.id}`"
                                :item="convertToItemData(doctrine)"
                            />
                        </template>
                        <div
                            v-else
                            class="text-center text-sm text-muted-foreground"
                        >
                            No sub-groups found
                        </div>
                    </div>

                    <!-- Doctrines -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <Heading
                                title="Doctrines"
                                description="Core beliefs of the religion"
                            />
                            <DoctrineFormModal @success="handleDoctrineAdded" :religion-id="religion.id">
                                <template #trigger>
                                    <Button size="sm">
                                        <Plus class="mr-2 h-4 w-4" />
                                        Add Doctrine
                                    </Button>
                                </template>
                            </DoctrineFormModal>
                        </div>

                        <div class="flex flex-col gap-4">
                            <template
                                v-if="
                                    religion.doctrines !== undefined &&
                                    religion.doctrines?.length > 0
                                "
                            >
                                <Item
                                    v-for="doctrine in religion.doctrines"
                                    :deleteItem="deleteDoctrine"
                                    :editItem="editDoctrine"
                                    :key="`doctrine-${doctrine.id}`"
                                    :item="convertToItemData(doctrine)"
                                />
                            </template>
                            <div
                                v-else
                                class="text-center text-sm text-muted-foreground"
                            >
                                No doctrines for this religion found
                            </div>

                            <div class="my-4 flex justify-center">
                                <Link
                                    class="hover:underline"
                                    :href="`/religions/${religion.id}/doctrines`"
                                    >See more</Link
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Correlations -->
                    <div
                        class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <Heading
                                title="Correlations"
                                description="Support, refutes, and nuggets related to this religion"
                            />
                            <CorrelationFormModal
                                v-if="
                                    religion.doctrines !== undefined &&
                                    religion.doctrines?.length > 0
                                "
                                :religion-id="religion.id"
                            >
                                <template #trigger>
                                    <Button size="sm">
                                        <Plus class="mr-2 h-4 w-4" />
                                        Add Correlation
                                    </Button>
                                </template>
                            </CorrelationFormModal>
                        </div>

                        <template
                            v-if="
                                religion.doctrines !== undefined &&
                                religion.doctrines?.length > 0
                            "
                        >
                            <Item
                                v-for="doctrine in religion.doctrines"
                                :key="`doctrine-${doctrine.id}`"
                                :item="convertToItemData(doctrine)"
                            />
                        </template>
                        <div
                            v-else
                            class="text-center text-sm text-muted-foreground"
                        >
                            No correlations to this religion found
                        </div>
                    </div>
                </div>
            </div>
        </Deferred>
    </AppLayout>
</template>
