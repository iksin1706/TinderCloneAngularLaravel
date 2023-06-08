import { Component, OnInit } from '@angular/core';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap/modal';
import { ToastrService } from 'ngx-toastr';
import { BansModalComponent } from 'src/app/modals/bans-modal/bans-modal.component';
import { RolesModalComponent } from 'src/app/modals/roles-modal/roles-modal.component';
import { User } from 'src/app/_models/user';
import { AdminService } from 'src/app/_services/admin.service';

@Component({
  selector: 'app-user-management',
  templateUrl: './user-management.component.html',
  styleUrls: ['./user-management.component.scss']
})
export class UserManagementComponent implements OnInit {
  users: User[] = [];
  bsModalRefRoles: BsModalRef<RolesModalComponent> = new BsModalRef<RolesModalComponent>();
  bsModalRefBans: BsModalRef<BansModalComponent> = new BsModalRef<BansModalComponent>();
  availableRoles = [
    'Admin',
    'Moderator',
    'User'
  ]

  constructor(private adminService: AdminService, private modalService: BsModalService, private toaster: ToastrService) {

  }

  getUsersWithRoles() {
    this.adminService.getUsersWithRoles().subscribe({
      next: users => this.users = users
    })
  }

  ngOnInit(): void {
    this.getUsersWithRoles();
  }

  openRolesModal(user: User) {
    const config = {
      class: 'modal-dialog-centered',
      initialState: {
        username: user.username,
        availableRoles: this.availableRoles,
        selectedRole: user.role
      }
    }
    this.bsModalRefRoles = this.modalService.show(RolesModalComponent, config);
    this.bsModalRefRoles.onHide?.subscribe({
      next: () => {
        const selectedRole = this.bsModalRefRoles.content?.selectedRole;
        if (selectedRole !== user.role && selectedRole) {
          this.adminService.updateUserRoles(user.username, selectedRole).subscribe({
            next: role => user.role = role
          })
        }
      }
    })
  }
  openBanModal(user: User) {
    const config = {
      class: 'modal-dialog-centered',
      initialState: {
        user: user,
        username: user.username
      }
    }
    this.bsModalRefBans = this.modalService.show(BansModalComponent, config);
  }

  unbanUser(user: User){
    this.adminService.unbanUser(user.username).subscribe({
      next:  reposnse => {
         this.toaster.success("User unbanned");
         user.isBlocked=false;
      }
    });
  }
}
