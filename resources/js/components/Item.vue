<script lang="ts" setup>
import { ItemData } from '@/types';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuPortal,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { PhArrowDown, PhArrowUp, PhChat, PhBookOpenText, PhWarning, PhDotsThree } from '@phosphor-icons/vue';

defineProps<{
    item: ItemData,
    deleteItem?: (id: number) => Promise<void>
    editItem?: (id: number) => Promise<void>
}>()
</script>
<template>
    <div class="flex flex-col p-8 bg-muted rounded-lg">
        <!-- Header -->
        <div class="flex flex-row items-center justify-between">
            <h3 class="text-lg font-semibold">{{ item.title }}</h3>

            <DropdownMenu v-if="deleteItem || editItem">
                <DropdownMenuTrigger as-child>
                    <button class="hover:text-base/50">
                        <PhDotsThree :size="20" weight="bold" />
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-56" align="end">
                    <DropdownMenuLabel>Item Actions</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                        <DropdownMenuItem v-if="editItem" @click="editItem(item.id)">
                            Edit
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="deleteItem" @click="deleteItem(item.id)">
                            Delete
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <!-- Content -->
        <p class="text-muted-foreground">{{ item.description }}</p>

        <!-- Footer -->
        <div class="mt-4 flex flex-row items-center gap-4">
            <div class="flex flex-row items-center gap-1.5">
                <PhArrowDown class="text-sky-500 hover:text-sky-700" :size="20" weight="bold" />
                <span>104</span>
                <PhArrowUp class="text-base hover:text-neutral-500" :size="20" />
            </div>

            <div class="flex flex-row items-center gap-2">
                <span>13</span>
                <PhChat :size="20" weight="fill" class="text-base hover:text-neutral-500 transition" />
            </div>

            <div class="flex flex-row items-center gap-2">
                <span>1</span>
                <PhBookOpenText :size="20" weight="fill" class="text-base hover:text-neutral-500 transition" />
            </div>

            <div class="flex flex-row items-center gap-2">
                <span>2</span>
                <PhWarning :size="20" weight="fill" class="text-base hover:text-neutral-500 transition" />
            </div>
        </div>
    </div>
</template>
