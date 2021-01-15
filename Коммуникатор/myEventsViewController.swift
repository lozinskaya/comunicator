//
//  myEventsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 13.01.2021.
//

import UIKit

class myEventsViewController: UIViewController {

    //массив данных
    static var myEvents : [[String]] = []
    static var myFinishedEvents : [[String]] = []
    let idCell = "MailCell"
    @IBOutlet weak var myEventsTable: UITableView!
    @IBOutlet weak var myFinishedEventsTable: UITableView!
    @IBOutlet weak var segmentControl: UISegmentedControl!
    override func viewDidLoad() {
        super.viewDidLoad()
        myEventsTable.dataSource = self
        myEventsTable.delegate = self
        
        myFinishedEventsTable.dataSource = self
        myFinishedEventsTable.delegate = self

        myEventsTable.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
        myFinishedEventsTable.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
        segmentControl.addTarget(self, action: #selector(selectedValue), for: .valueChanged)
    }
    
    @objc func selectedValue(target: UISegmentedControl) {
        if target == self.segmentControl {
            let segmentIndex = target.selectedSegmentIndex
            
            if (segmentIndex == 0) {
                myEventsTable.isHidden = false
                myFinishedEventsTable.isHidden = true
            }
            else {
                myEventsTable.isHidden = true
                myFinishedEventsTable.isHidden = false
            }
            
        }
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}

extension myEventsViewController: UITableViewDataSource, UITableViewDelegate{
    
    //количество создаваемых клеток
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if (tableView == myEventsTable){
            return self.myEvents.count
        } else {
            return self.myFinishedEvents.count
        }
    }
    //создание клетки
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = myEventsTable.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
        var dataArray = self.myEvents
        
        if (tableView == self.myFinishedEventsTable){
            cell = myFinishedEventsTable.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
            dataArray = self.myFinishedEvents;
        }
        
        cell.titleEvent.text = dataArray[indexPath.row][1]
        cell.descriptionEvent.text = dataArray[indexPath.row][2]

        cell.timeEvent.text = dataArray[indexPath.row][3]
        cell.countPersonsEvent.text = dataArray[indexPath.row][4]

        cell.imgEvent.image = UIImage(named: "eventImg")
        let image_url = dataArray[indexPath.row][5]
        if image_url != "" {
            cell.imgEvent.load(url: image_url.getCleanedURL()!)
        }
        
        return cell
    }
    //высота клетки
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 313.0
    }
    //вывод индекса выбранной строки
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        print("\(indexPath.row)")
        performSegue(withIdentifier: "showdetail", sender: self)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if let destination = segue.destination as? DetailVC {
            destination.chooseCell = myEventsViewController.myEvents[(myEventsTable.indexPathForSelectedRow?.row)!]
        }
    }
}
