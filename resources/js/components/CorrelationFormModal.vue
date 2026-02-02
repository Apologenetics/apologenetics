<script setup lang="ts">
import { ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import CorrelationForm from '@/components/CorrelationForm.vue';
import { type Correlation } from '@/types';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    correlation?: Correlation;
    religionId: number;
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
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ correlation ? 'Edit Correlation' : 'Add Correlation' }}
                </DialogTitle>
            </DialogHeader>
            <CorrelationForm
                :correlation="correlation"
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
                        {{ correlation ? 'Save' : 'Add' }}
                    </Button>
                </template>
            </CorrelationForm>
        </DialogContent>
    </Dialog>
</template>
