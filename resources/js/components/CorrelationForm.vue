<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
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
import { type Correlation, type CorrelationForm as CorrelationFormType } from '@/types';
import { CorrelationType } from '@/types/enums';
import { watch } from 'vue';

const props = defineProps<{
    correlation?: Correlation;
    religionId: number;
    onSuccess?: () => void;
}>();

const emit = defineEmits<{
    submit: [data: CorrelationFormType];
    cancel: [];
    success: [];
}>();

const form = useForm<CorrelationFormType>({
    correlatable_to_type: props.correlation?.correlatable_to_type || '',
    correlatable_to_id: props.correlation?.correlatable_to_id || 0,
    description: props.correlation?.description ?? '',
    type: props.correlation?.type ?? CorrelationType.Support,
});

// Watch for correlation prop changes
watch(
    () => props.correlation,
    (newCorrelation) => {
        if (newCorrelation) {
            form.correlatable_to_type = newCorrelation.correlatable_to_type;
            form.correlatable_to_id = newCorrelation.correlatable_to_id;
            form.description = newCorrelation.description || null;
            form.type = newCorrelation.type;
        } else {
            form.reset();
        }
    },
    { deep: true },
);

const handleSubmit = () => {
    if (props.correlation === undefined) {
        form.post(`/placeholder/religions/${props.religionId}/correlations`, {
            preserveScroll: true,
            onSuccess: () => {
                console.log('success submit correlation form')
                form.reset();
                emit('success');
            },
            onError: (errors) => {
                console.error('Validation errors:', errors);
            },
        });
    } else {
        form.put(`/placeholder/religions/${props.religionId}/correlations/${props.correlation.correlatable_from_id}`, {
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

const correlationTypeLabels: Record<CorrelationType, string> = {
    [CorrelationType.Support]: 'Support',
    [CorrelationType.Refute]: 'Refute',
    [CorrelationType.Nugget]: 'Nugget',
    [CorrelationType.Reference]: 'Reference',
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="space-y-4">
            <!-- Type Field -->
            <div class="space-y-2">
                <Label for="type">
                    Type <span class="text-destructive">*</span>
                </Label>
                <Select v-model="form.type">
                    <SelectTrigger>
                        <SelectValue placeholder="Select correlation type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in correlationTypeLabels"
                            :key="value"
                            :value="String(value)"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.type" />
            </div>

            <!-- Target Type Field (placeholder for now) -->
            <div class="space-y-2">
                <Label for="correlatable_to_type">
                    Target Type <span class="text-destructive">*</span>
                </Label>
                <Select v-model="form.correlatable_to_type">
                    <SelectTrigger>
                        <SelectValue placeholder="Select target type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="App\\Models\\Religion">Religion</SelectItem>
                        <SelectItem value="App\\Models\\Faith">Faith</SelectItem>
                        <SelectItem value="App\\Models\\Doctrine">Doctrine</SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.correlatable_to_type" />
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea
                    id="description"
                    v-model="form.description!"
                    placeholder="Enter correlation description"
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
