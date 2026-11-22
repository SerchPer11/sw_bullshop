import { usePage } from "@inertiajs/vue3";

export const useCan = (permission) => !!usePage().props.auth?.can?.[permission];
export const useRole = (role) => !!usePage().props.auth?.roles?.[role];
export const useRoles = (roles) =>
    Object.keys(usePage().props.auth?.roles ?? {}).some((role) =>
        Object.values(roles).includes(role)
    );

export const verifyPermission = (permission) => {
    if(!permission) {
        return true;
    }
    return useCan(permission);
};