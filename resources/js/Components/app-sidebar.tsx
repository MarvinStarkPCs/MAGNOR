import { Link } from '@inertiajs/react';
import { LayoutGrid, Users, Package, FileText, ClipboardList, Settings, Warehouse, BarChart3 } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    { title: 'Dashboard',       href: dashboard(),          icon: LayoutGrid  },
    { title: 'Recibos',         href: '/recibos',           icon: FileText    },
    { title: 'Recicladores',    href: '/recicladores',      icon: Users       },
    { title: 'Materiales',      href: '/materiales',        icon: Package     },
    { title: 'Inventario',      href: '/inventario',        icon: BarChart3   },
    { title: 'Bodegas',         href: '/bodegas',           icon: Warehouse   },
    { title: 'Registro',        href: '/registro',          icon: ClipboardList },
];

const settingsNavItems: NavItem[] = [
    { title: 'Configuración',   href: '/settings/empresa',  icon: Settings    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
                <NavMain items={settingsNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
