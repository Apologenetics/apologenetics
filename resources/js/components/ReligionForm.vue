<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { type Religion, type ReligionForm as ReligionFormType } from '@/types';
import { watch } from 'vue';
import ReligionController from '@/actions/App/Http/Controllers/ReligionController';

const props = defineProps<{
    religion?: Religion;
    religions?: Religion[];
    parentId?: number; // For creating sub-groups
    onSuccess?: () => void;
}>();

const emit = defineEmits<{
    submit: [data: ReligionFormType];
    cancel: [];
    success: [];
}>();

const form = useForm<ReligionFormType>({
    name: props.religion?.name || '',
    description: props.religion?.description ?? '',
    parent_id: props.religion?.parent_id
        ? props.religion.parent_id
        : props.parentId ?? null
});

// Watch for religion prop changes (when editing different religions)
watch(
    () => props.religion,
    (newReligion) => {
        if (newReligion) {
            form.name = newReligion.name;
            form.description = newReligion.description || null;
            form.parent_id = newReligion.parent_id
                ? newReligion.parent_id
                : null;
        } else {
            form.reset();
        }
    },
    { deep: true },
);

const handleSubmit = () => {
    if (props.religion === undefined) {
        form.post(ReligionController.store().url, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('success submit form')
                form.reset();
                emit('success');
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });
    } else {
        form.put(ReligionController.update(props.religion.id).url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                emit('success');
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });
    }
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="space-y-4">
            <!-- Name Field -->
            <div class="space-y-2">
                <Label for="name">
                    Name <span class="text-destructive">*</span>
                </Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Enter religion name"
                    required
                />
                <InputError :message="form.errors.name" />
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea
                    id="description"
                    v-model="form.description!"
                    placeholder="Enter religion description"
                    rows="4"
                />
                <InputError :message="form.errors.description" />
            </div>

            <!-- Parent Religion Field -->
            <div class="space-y-2">
                <Label for="parent_id">Parent Religion</Label>
                <Select v-model="form.parent_id">
                    <SelectTrigger>
                        <SelectValue
                            placeholder="Select a parent religion (optional)"
                        />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="rel in religions ?? []"
                            :key="rel.id"
                            :value="String(rel.id)"
                            :disabled="religion && rel.id === religion.id"
                        >
                            {{ rel.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.parent_id" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <slot name="buttons" :processing="form.processing" />
        </div>
    </form>
</template>
