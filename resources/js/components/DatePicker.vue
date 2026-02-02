<script lang="ts" setup>
import { type DateValue, parseDate } from '@internationalized/date';
import { Calendar } from '@/components/ui/calendar';
import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = withDefaults(defineProps<{
    modelValue?: string;
    placeholder?: string;
    defaultValue?: string;
}>(), {
    placeholder: 'Pick a date',
});

const emit = defineEmits<{
    'update:modelValue': [value: string | undefined];
}>();

// Internal state for the date value
const internalValue = ref<DateValue | undefined>(
    props.modelValue ? parseDate(props.modelValue) :
    props.defaultValue ? parseDate(props.defaultValue) :
    undefined
);

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
    const newDateValue = newValue ? parseDate(newValue) : undefined;
    if (newDateValue?.toString() !== internalValue.value?.toString()) {
        internalValue.value = newDateValue;
    }
});

// Watch for internal changes and emit string value
watch(internalValue, (newValue) => {
    const newStringValue = newValue ? newValue.toString() : undefined;
    emit('update:modelValue', newStringValue);
});

const displayValue = computed(() => {
    if (!internalValue.value) return props.placeholder;

    // Format the date for display (you can customize this)
    const date = new Date(internalValue.value.year, internalValue.value.month - 1, internalValue.value.day);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="
                    cn(
                        'w-full justify-start text-left font-normal',
                        !internalValue && 'text-muted-foreground'
                    )
                "
            >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ displayValue }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar
                v-model="internalValue"
                initial-focus
            />
        </PopoverContent>
    </Popover>
</template>
