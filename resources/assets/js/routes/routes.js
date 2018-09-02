import DashboardLayout from '@/pages/Layout/DashboardLayout.vue'

import Dashboard from '@/pages/Dashboard.vue'
import UserProfile from '@/pages/UserProfile.vue'
import TableList from '@/pages/TableList.vue'
import Typography from '@/pages/Typography.vue'
import Icons from '@/pages/Icons.vue'
import Maps from '@/pages/Maps.vue'
import Notifications from '@/pages/Notifications.vue'

import Employee from '@/pages/Employee.vue'
import Departament from '@/pages/Departament.vue'
import Schedule from '@/pages/Schedule.vue'
import Relationship from '@/pages/Relationship.vue'
import Assistance from '@/pages/Assistance.vue'

const routes = [
  {
    path: '/',
    component: DashboardLayout,
    redirect: '/dashboard',
    children: [
      {
        path: 'dashboard',
        name: 'Dashboard',
        component: Dashboard
      },
      {
        path: 'employee',
        name: 'Administración de Funcionarios',
        component: Employee
      },
      {
        path: 'departament',
        name: 'Administración de Departamentos',
        component: Departament
      },
      {
        path: 'schedule',
        name: 'Administración de Horarios',
        component: Schedule
      },
      {
        path: 'relationship',
        name: 'Administración de Relaciones',
        component: Relationship
      },
      {
        path: 'assistance',
        name: 'Registros de Asistencia',
        component: Assistance
      },
      {
        path: 'user',
        name: 'Usuario',
        component: UserProfile
      },
      {
        path: 'table',
        name: 'Table List',
        component: TableList
      },
      {
        path: 'typography',
        name: 'Typography',
        component: Typography
      },
      {
        path: 'icons',
        name: 'Icons',
        component: Icons
      },
      {
        path: 'maps',
        name: 'Maps',
        meta: {
          hideFooter: true
        },
        component: Maps

      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: Notifications
      }
    ]
  }
]

export default routes
