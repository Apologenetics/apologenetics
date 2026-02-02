<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import type { Religion } from '@/types';
import { Deferred, Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import DatePicker from '@/components/DatePicker.vue';

defineProps<{
    religions?: Religion[];
}>();

const form = useForm({
    name: '',
    username: '',
    email: '',
    gender: '',
    password: '',
    password_confirmation: '',
    religion_id: '',
    date_converted: '',
    reason_converted: '',
});

const submit = () => {
    form.post(store.url(), {
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <AuthBase
        title="Create an account"
        description="Enter your details below to create your account"
    >
        <Head title="Register" />

        <form
            @submit.prevent="submit"
            class="flex w-full flex-col items-center gap-6"
        >
            <div
                class="grid w-full gap-6 rounded-xl md:max-w-2xl md:grid-cols-2"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Full name"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="username">Username</Label>
                    <Input
                        id="username"
                        type="text"
                        required
                        autofocus
                        :tabindex="2"
                        autocomplete="username"
                        v-model="form.username"
                        placeholder="Username"
                    />
                    <InputError :message="form.errors.username" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="3"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="gender">Gender</Label>
                    <Select v-model="form.gender">
                        <SelectTrigger :tabindex="4">
                            <SelectValue placeholder="Select a gender" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="male">Male</SelectItem>
                            <SelectItem value="female">Female</SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.gender" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="6"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>

            <Separator class="md:max-w-2xl" />

            <Deferred data="religions">
                <template #fallback>
                    <LoaderCircle class="h-4 w-4 animate-spin" />
                </template>
                <div
                    class="grid w-full gap-6 rounded-xl md:max-w-2xl md:grid-cols-2"
                >
                    <div class="grid gap-2">
                        <Label for="religion_id">Religion</Label>
                        <Select v-model="form.religion_id">
                            <SelectTrigger>
                                <SelectValue
                                    placeholder="Select a religion..."
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="religion in religions ?? []"
                                    :key="religion.id"
                                    :value="String(religion.id)"
                                >
                                    {{ religion.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <InputError :message="form.errors.religion_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="date_converted">Date Converted</Label>

                        <DatePicker
                            v-model="form.date_converted"
                            placeholder="Select date converted..."
                        />

                        <InputError :message="form.errors.date_converted" />
                    </div>

                    <div class="grid gap-2 md:col-span-2">
                        <Label for="reason_converted">Reason Converted</Label>

                        <Textarea
                            v-model="form.reason_converted"
                            placeholder="Enter reason converted..."
                        />

                        <InputError :message="form.errors.reason_converted" />
                    </div>
                </div>
            </Deferred>

            <div class="flex w-full flex-col items-center gap-6">
                <Button
                    type="submit"
                    class="mt-2 w-full md:max-w-2xl"
                    tabindex="7"
                    :disabled="form.processing"
                    data-test="register-user-button"
                >
                    <LoaderCircle
                        v-if="form.processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Create account
                </Button>

                <div class="text-center text-sm text-muted-foreground">
                    Already have an account?
                    <TextLink
                        :href="login()"
                        class="underline underline-offset-4"
                        :tabindex="8"
                        >Log in</TextLink
                    >
                </div>
            </div>
        </form>
    </AuthBase>
</template>
