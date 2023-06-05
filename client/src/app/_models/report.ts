export interface Report {
    id: string;
    reported_id: number;
    reporting_id: number;
    reported_username: string;
    reporting_username: string;
    reason: string;
    created_at: Date;
}