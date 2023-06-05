import { Component } from '@angular/core';
import { BsModalRef, BsModalService } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { BansModalComponent } from 'src/app/modals/bans-modal/bans-modal.component';
import { Report } from 'src/app/_models/report';
import { User } from 'src/app/_models/user';
import { AdminService } from 'src/app/_services/admin.service';

@Component({
  selector: 'app-reports',
  templateUrl: './reports.component.html',
  styleUrls: ['./reports.component.scss']
})
export class ReportsComponent {

  reports: Report[] = [];
  bsModalRefBans: BsModalRef<BansModalComponent> = new BsModalRef<BansModalComponent>();

  constructor(private adminService: AdminService, private modalService: BsModalService, private toaster: ToastrService) {

  }

  ngOnInit(): void {
    this.loadReports();
  }

  loadReports(): void {
    this.adminService.getReports().subscribe({
      next: response => {
          this.reports = response;
        }
      });
  }

  openBanModal(username: string) {
    const config = {
      class: 'modal-dialog-centered',
      initialState: {
        username: username,
      }
    }
    this.bsModalRefBans = this.modalService.show(BansModalComponent, config);
  }
}
