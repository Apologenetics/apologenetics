<script lang="ts" setup>
import type { CalendarRootEmits, CalendarRootProps } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit } from "@vueuse/core"
import { CalendarRoot, useForwardPropsEmits } from "reka-ui"
import { cn } from "@/lib/utils"
import { CalendarCell, CalendarCellTrigger, CalendarGrid, CalendarGridBody, CalendarGridHead, CalendarGridRow, CalendarHeadCell, CalendarHeader, CalendarHeading, CalendarNextButton, CalendarPrevButton } from "."
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { computed, ref, watch } from 'vue'

const props = defineProps<CalendarRootProps & { class?: HTMLAttributes["class"] }>()

const emits = defineEmits<CalendarRootEmits>()

const delegatedProps = reactiveOmit(props, "class")

const forwarded = useForwardPropsEmits(delegatedProps, emits)

// Month and year selection
const months = [
  'January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'
]

const currentYear = new Date().getFullYear()
const years = Array.from({ length: 100 }, (_, i) => currentYear - 50 + i)

// Local placeholder ref
const localPlaceholder = ref(props.placeholder)

// Watch for external placeholder changes
watch(() => props.placeholder, (newValue) => {
  if (newValue) {
    localPlaceholder.value = newValue
  }
})

// Watch for local changes and emit
watch(localPlaceholder, (newValue) => {
  if (newValue) {
    emits('update:placeholder', newValue)
  }
}, { deep: true })

const handleMonthChange = (monthIndex: string) => {
  if (localPlaceholder.value) {
    localPlaceholder.value = localPlaceholder.value.set({ month: parseInt(monthIndex) })
  }
}

const handleYearChange = (year: string) => {
  if (localPlaceholder.value) {
    localPlaceholder.value = localPlaceholder.value.set({ year: parseInt(year) })
  }
}

const currentMonth = computed(() => {
  return localPlaceholder.value ? String(localPlaceholder.value.month) : '1'
})

const currentYearValue = computed(() => {
  return localPlaceholder.value ? String(localPlaceholder.value.year) : String(currentYear)
})
</script>

<template>
  <CalendarRoot
    v-slot="{ grid, weekDays }"
    :class="cn('p-3', props.class)"
    v-bind="forwarded"
  >
    <CalendarHeader class="flex items-center justify-between">
      <CalendarPrevButton />

      <div class="flex items-center gap-1">
        <Select :model-value="currentMonth" @update:model-value="handleMonthChange">
          <SelectTrigger class="h-7 w-[110px] text-xs">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="(month, index) in months"
              :key="month"
              :value="String(index + 1)"
            >
              {{ month }}
            </SelectItem>
          </SelectContent>
        </Select>

        <Select :model-value="currentYearValue" @update:model-value="handleYearChange">
          <SelectTrigger class="h-7 w-[80px] text-xs">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem
              v-for="year in years"
              :key="year"
              :value="String(year)"
            >
              {{ year }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>

      <CalendarNextButton />
    </CalendarHeader>

    <div class="flex flex-col gap-y-4 mt-4 sm:flex-row sm:gap-x-4 sm:gap-y-0">
      <CalendarGrid v-for="month in grid" :key="month.value.toString()">
        <CalendarGridHead>
          <CalendarGridRow>
            <CalendarHeadCell
              v-for="day in weekDays" :key="day"
            >
              {{ day }}
            </CalendarHeadCell>
          </CalendarGridRow>
        </CalendarGridHead>
        <CalendarGridBody>
          <CalendarGridRow v-for="(weekDates, index) in month.rows" :key="`weekDate-${index}`" class="mt-2 w-full">
            <CalendarCell
              v-for="weekDate in weekDates"
              :key="weekDate.toString()"
              :date="weekDate"
            >
              <CalendarCellTrigger
                :day="weekDate"
                :month="month.value"
              />
            </CalendarCell>
          </CalendarGridRow>
        </CalendarGridBody>
      </CalendarGrid>
    </div>
  </CalendarRoot>
</template>
