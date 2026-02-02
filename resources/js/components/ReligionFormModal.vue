<script setup lang="ts">
import { ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogClose,
    DialogTrigger,
} from '@/components/ui/dialog';
import ReligionForm from '@/components/ReligionForm.vue';
import { type Religion } from '@/types';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    religion?: Religion;
    religions?: Religion[];
    religionId?: number; // parent_id for sub-groups
}>();

const isOpen = ref(false);

const handleSuccess = () => {
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[600px]">
            <DialogHeader>
                <DialogTitle>
                    {{ religion ? 'Update' : 'Create' }} Religion
                </DialogTitle>
                <DialogDescription>
                    {{
                        religion
                            ? 'Update the religion details below.'
                            : 'Fill in the details below to create a new religion.'
                    }}
                </DialogDescription>
            </DialogHeader>

            <ReligionForm
                :religion="religion"
                :religions="religions"
                :parent-id="religionId"
                @success="handleSuccess"
            >
                <template #buttons="{ processing }">
                    <DialogClose as-child>
                        <Button variant="outline" :disabled="processing">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" :disabled="processing">
                        <LoaderCircle
                            v-if="processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        Save
                    </Button>
                </template>
            </ReligionForm>
        </DialogContent>
    </Dialog>
</template>
