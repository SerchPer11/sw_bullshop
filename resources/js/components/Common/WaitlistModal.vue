<script setup>
import { useForm } from '@inertiajs/vue3';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import NeoInput from '@/components/Common/NeoInput.vue';
import { BellRing } from 'lucide-vue-next';

const props = defineProps({
    open: Boolean,
    itemName: { type: String, default: '' },
    itemId: { type: String, default: null },
    itemType: { type: String, default: 'product' }, // 'product' o 'package'
});

const emit = defineEmits(['update:open']);

const form = useForm({
    email: '',
    item_id: props.itemId,
    item_type: props.itemType,
});
console.log('itemId:', props.itemId);
console.log('itemType:', props.itemType);
const submit = () => {
    form.item_id = props.itemId;
    form.item_type = props.itemType;

    form.post(route('waitlist.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('email');
            emit('update:open', false)
        },
    });
};
</script>

<template>
    <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
        <DialogContent
            class="w-auto md:w-auto mx-auto md:max-w-md border-4 border-bull-blue rounded-none shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] bg-bull-cream">
            <DialogHeader>
                <DialogTitle class="text-xl font-black uppercase text-bull-blue">
                    ¡Te avisamos primero!
                </DialogTitle>
                <DialogDescription class="text-slate-600">
                    Déjanos tu correo y te notificaremos en cuanto activemos envíos nacionales
                    <span v-if="itemName" class="font-bold text-bull-blue"> para {{ itemName }}</span>.
                </DialogDescription>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4 mt-2">
                <div>
                    <NeoInput label="Correo Electrónico" id="email" v-model="form.email" placeholder="juan123@gmail.com"
                        :required="true" class="w-full" type="email" :error="form.errors?.email" validation="email" />
                </div>

                <Button variant="neo-neon" type="submit" :disabled="form.processing"
                    class="w-full flex items-center justify-center gap-2">
                    Avisarme cuando esté disponible
                    <BellRing class="w-4 h-4 md:w-5 h-5" />
                </Button>
            </form>
        </DialogContent>
    </Dialog>
</template>