<script setup lang="ts">
import { ref } from 'vue';

const clipFile = ref<File | null>(null);
const description = ref('');
const errors = ref<Record<string, any>>({});
const submitting = ref(false);
const success = ref('');
const fileInputRef = ref<HTMLInputElement | null>(null);

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement | null;
    const file = target?.files && target.files[0] ? target.files[0] : null;
    clipFile.value = file;
    if (clipFile.value && errors.value.clip) delete errors.value.clip;
    console.debug('onFileChange:', { file });
}

async function submitForm() {
    errors.value = {};
    success.value = '';
    if (!clipFile.value) {
        errors.value.clip = ['The clip file is required.'];
        return;
    }
    if (!description.value || !description.value.trim()) {
        errors.value.description = ['The description is required.'];
        return;
    }

    const formData = new FormData();
    formData.append('clip', clipFile.value);
    formData.append('description', description.value);

    // Debug: log FormData entries (file name/size/type) so we can confirm the client is populating the payload
    console.debug('Preparing upload, form entries:');
    for (const [key, value] of Array.from(formData.entries())) {
        if (value instanceof File) {
            console.debug(
                `  ${key}: File name=${value.name}, size=${value.size}, type=${value.type}`,
            );
        } else {
            console.debug(`  ${key}: ${String(value)}`);
        }
    }

    submitting.value = true;

    try {
        const res = await fetch('/api/clip', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin', // keep cookies/session if needed
        });

        // Read raw text for debugging if JSON parse fails
        const text = await res.text();
        let data = null;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.warn('Response is not valid JSON, raw text:', text);
        }

        console.debug('Server response', {
            status: res.status,
            ok: res.ok,
            data,
            rawText: text,
        });

        if (res.ok) {
            success.value =
                data && data.message
                    ? data.message
                    : 'Clip uploaded successfully.';
            // reset form
            clipFile.value = null;
            description.value = '';
            if (fileInputRef.value) {
                fileInputRef.value.value = '';
            }
        } else if (res.status === 422 && data && data.errors) {
            errors.value = data.errors;
        } else {
            errors.value.general = [
                data && data.message
                    ? data.message
                    : `Request failed with status ${res.status}`,
            ];
        }
    } catch (err: any) {
        console.error('Network/fetch error', err);
        errors.value.general = [err?.message || 'Network error'];
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <form @submit.prevent="submitForm" novalidate class="clip-form">
        <div class="field">
            <label for="clip">Clip (file)</label>
            <input
                id="clip-input"
                ref="fileInputRef"
                type="file"
                name="clip"
                @change="onFileChange"
            />
            <div
                v-if="errors.clip"
                class="error"
                v-for="(msg, i) in errors.clip"
                :key="i"
            >
                {{ msg }}
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea
                id="description"
                v-model="description"
                rows="4"
                placeholder="Describe the clip..."
            ></textarea>
            <div
                v-if="errors.description"
                class="error"
                v-for="(msg, i) in errors.description"
                :key="i"
            >
                {{ msg }}
            </div>
        </div>

        <div
            v-if="errors.general"
            class="error"
            v-for="(msg, i) in errors.general"
            :key="i"
        >
            {{ msg }}
        </div>

        <div class="actions">
            <button type="submit" :disabled="submitting">Submit</button>
        </div>

        <div v-if="success" class="success">{{ success }}</div>
    </form>
</template>

<style scoped>
.clip-form {
    max-width: 480px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.field label {
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
}
.field input[type='file'] {
    display: block;
}
textarea {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}
.actions {
    margin-top: 6px;
}
button[disabled] {
    opacity: 0.6;
    cursor: not-allowed;
}
.error {
    color: #b00020;
    font-size: 0.9rem;
    margin-top: 4px;
}
.success {
    color: #0a7a0a;
    font-size: 0.95rem;
    margin-top: 8px;
}
</style>
