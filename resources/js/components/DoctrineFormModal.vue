<script setup lang="ts">
import DoctrineForm from '@/components/DoctrineForm.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { type Doctrine, type DoctrineForm as DoctrineFormType } from '@/types';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    doctrine?: Doctrine;
    religionId: number;
}>();

const emit = defineEmits<{
    success: [data: DoctrineFormType];
}>();

const isOpen = ref(false);

const handleSuccess = (formData: DoctrineFormType) => {
    emit('success', formData);
    isOpen.value = false;
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot name="trigger" />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ doctrine ? 'Edit Doctrine' : 'Add Doctrine' }}
                </DialogTitle>
            </DialogHeader>
            <DoctrineForm
                :doctrine="doctrine"
                :religion-id="religionId"
                @success="handleSuccess"
            >
                <template #buttons="{ processing }">
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="processing"
                        @click="isOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="processing">
                        <LoaderCircle
                            v-if="processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{ doctrine ? 'Save' : 'Add' }}
                    </Button>
                </template>
            </DoctrineForm>
        </DialogContent>
    </Dialog>
</template>
