<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { type Doctrine, type DoctrineForm as DoctrineFormType } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps<{
    doctrine?: Doctrine;
    religionId: number;
}>();

const emit = defineEmits<{
    submit: [data: DoctrineFormType];
    cancel: [];
    success: [data: DoctrineFormType];
}>();

const form = useForm<DoctrineFormType>({
    title: props.doctrine?.title || '',
    description: props.doctrine?.description ?? '',
});

// Watch for doctrine prop changes (when editing different doctrines)
watch(
    () => props.doctrine,
    (newDoctrine) => {
        if (newDoctrine) {
            form.title = newDoctrine.title;
            form.description = newDoctrine.description || null;
        } else {
            form.reset();
        }
    },
    { deep: true },
);

const handleSubmit = () => {
    if (props.doctrine === undefined) {
        form.post(`/religions/${props.religionId}/doctrines`, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('success submit doctrine form');
                form.reset();
                emit('success', form.data());
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });
    } else {
        form.put(
            `/religions/${props.religionId}/doctrines/${props.doctrine.id}`,
            {
                preserveScroll: true,
                onSuccess: () => {
                    form.reset();
                    emit('success', form.data());
                },
                onError: (errors) => {
                    console.error('Validation errors:', errors);
                },
            },
        );
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="space-y-4">
            <!-- Title Field -->
            <div class="space-y-2">
                <Label for="title">
                    Title <span class="text-destructive">*</span>
                </Label>
                <Input
                    id="title"
                    v-model="form.title"
                    type="text"
                    placeholder="Enter doctrine title"
                    required
                />
                <InputError :message="form.errors.title" />
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea
                    id="description"
                    v-model="form.description!"
                    placeholder="Enter doctrine description"
                    rows="4"
                />
                <InputError :message="form.errors.description" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <slot name="buttons" :processing="form.processing" />
        </div>
    </form>
</template>
