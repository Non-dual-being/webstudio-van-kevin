import { onBeforeUnmount, ref, type Ref } from "vue";
import type { ApiResponse, ApiRateLimitError, ApiOk, ApiServerError } from "./../types/http/ApiResponse";

import { requestDemo } from '../services/http/demoSession';
const SUBMIT_URL = '/demo-api/complete';
const SLOW_TRESHOLD_MS: number = 4000;

export type SubmitState = "idle" | "pending" | "slow" | "error" | "success";
type serverError = string | null;

export type UseFormSubmitReturn = {
    state: Ref<SubmitState>
    formError: Ref<serverError>;
    submit: (formData: FormData) => Promise<ApiResponse>
    reset: () => void;
    clearFormError: () => void;
}

export function useFormSubmit(): UseFormSubmitReturn {
    const state = ref<SubmitState>("idle")
    const formError = ref<serverError>(null);

    function reset(): void {
        state.value = "idle";
        formError.value = null;
    }

    function clearFormError(): void {
        formError.value = null;
    }


    let active = true;
    let timer: ReturnType<typeof setTimeout> | undefined;
    onBeforeUnmount(() => { active = false; clearTimeout(timer); });

    async function submit(formData: FormData): Promise<ApiResponse> {
        state.value = "pending";
        formError.value = null;

        const slowTimer = timer = setTimeout(() => {
            if (state.value === "pending") state.value = "slow";
        }, SLOW_TRESHOLD_MS);

        try {
            const response = await requestDemo<ApiResponse>(SUBMIT_URL, {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // TOEGEVOEGD: voor PHP detectie
                }
            });

            let data = null

            /**
             * validation response is ok or not ok
             * not ok consist of a validation error or a server error
             * Both are json
             */

            try {
                if (!active) throw new DOMException("Demo opnieuw gestart.", "AbortError");
                const Jsondata = response.body;
                data = Jsondata
            } catch {
                clearTimeout(slowTimer);
                state.value = "error";
                formError.value = "De server gaf een fout terug"
                return {
                    ok: false,
                    code: 500,
                    type: "server"
                } as ApiServerError

            }

            clearTimeout(slowTimer);


            // GECORRIGEERD: Betere response parsing
            if (data.ok === true) {
                state.value = "success";
                return data as ApiOk;
            }

            switch (data.type) {
                case "validation":
                    state.value = "idle";
                    formError.value = (data.fieldErrors as Record<string, string>).demo ?? null;
                    return data;

                case "rate-limit":
                    state.value = "idle";
                    formError.value = `Een nieuwe aanvraag opsturen kan na ${data.retryAfter} seconden`;
                    return data as ApiRateLimitError;

                case "server":
                    state.value = "error";
                    return data as ApiServerError;

                default:
                    throw new Error("Onverwachte server response");
            };


        } catch {
            clearTimeout(slowTimer);
            state.value = "idle";
            /**
             * state is idle preveting the navigation to error page and ensure the flash trigger above btn
             */

            const message = "De demo kon niet worden gecontroleerd. Controleer de verbinding en probeer opnieuw.";

            formError.value = message;

            const networkError: ApiServerError = {
                ok: false,
                code: 502,
                type: "server"
            }

            return networkError;
        }
    }

    return { state, formError, submit, reset, clearFormError}
}
